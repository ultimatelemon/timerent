<?php

namespace App\Http\Controllers\Application;

use App\Http\Controllers\ApiController;
use App\Http\Requests\Application\ApplicationLoginRequest;
use App\Http\Requests\Application\StoreMember;
use App\Http\Requests\Authentication\LoginRequest;
use App\Models\Member;
use App\Models\User;
use App\Models\Venue;
use App\Notifications\Application\ApplicationEmailVerification;
use App\Notifications\Application\ApplicationEmailVerified;
use App\Notifications\ApplicationPasswordResetRequest;
use App\Notifications\ApplicationPasswordResetted;
use App\Notifications\EmailVerified;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;
use Jenssegers\Agent\Agent;
use Laravel\Sanctum\PersonalAccessToken;

class ApplicationAuthenticationController extends ApiController
{
    /**
     * Store a new member to a venue
     *
     * @param StoreMember $request
     * @return JsonResponse
     */
    public function storeMember(StoreMember $request): JsonResponse
    {
        $validatedRequest = $request->validated();
        $venue = Venue::where('subdomain', $validatedRequest['subdomain'])->firstOrFail();

        $member = new Member();
        $member->venue_id = $venue->id;
        $member->name = $validatedRequest["name"];
        $member->email = $validatedRequest["email"];
        $member->password = Hash::make($validatedRequest["password"]);
        $member->role_id = '509ab95a-9dbc-4857-a142-c3a1fa9a9812';
        $member->email_verification_token = Str::random(64);
        $member->email_verification_token_expires_at = Carbon::now()->addHours(2);
        $member->save();

        $venue->members()->save($member);

        $url = 'https://' . $venue->subdomain . '.' . env('MAIN_DOMAIN') . '/email/verify?member=' . $member->id . '&token=' . Hash::make($member->email . $member->email_verification_token);

        $member->notify(new ApplicationEmailVerification($url, $member, $venue));

        return $this->success();
    }

    /**
     * Create a new token
     *
     * @param ApplicationLoginRequest $request
     * @return JsonResponse
     */
    public function createToken(ApplicationLoginRequest $request): JsonResponse
    {
        $validatedRequest = $request->validated();
        $member = Member::where('email', strtolower($validatedRequest['email']))->first();

        if (!$member || !Hash::check($request->password, $member->password))
            return $this->error([__('Wrong email or password')], 400);

        if (!$member->email_verified_at)
            return $this->error(['email_verification' => __('Email not verified')], 400);

        $agent = new Agent();
        $sa = $agent->device() . ', ' . $agent->platform() . ' (' . $agent->browser() . ')';
        $device_name = $request->get('device_name', $sa);

        $token = $member->createToken($device_name);
        $token->accessToken->expires_at = ($request->minutes !== null ? Carbon::now()->addMinutes($request->minutes) : null);
        $token->accessToken->save();

        return $this->success(['token' => $token->plainTextToken]);
    }

    /**
     * Revoke specific or current token
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function revokeToken(Request $request): JsonResponse
    {
        if ($request->has('id') && $request->get('id') != null) {
            $token = $request->member->tokens()->where('id', $request->get('id'))->firstOrFail();
            $token->delete();
        } else {
            $token = explode('|', $request->bearerToken())[1];
            $tokkie = PersonalAccessToken::where('token', hash('sha256', $token))->first();
            $tokkie->delete();
        }
        return $this->success();
    }

    /**
     * Resend an email verification email
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function resendVerifyEmail(Request $request): JsonResponse
    {
        $validatedRequest = $request->validate([
            'email' => 'required|exists:members,email',
        ]);

        $member = Member::where('email', $validatedRequest['email'])->firstOrFail();
        if ($member->email_verified_at) return $this->error();

        $member->email_verification_token = Str::random(64);
        $member->email_verification_token_expires_at = Carbon::now()->addHours(2);
        $member->save();

        // TODO: Member email verification
//        Mail::to($validatedRequest['email'])->send(new Verify)

        return $this->success();
    }

    public function verifyEmail(Request $request): JsonResponse
    {
//        return $this->error();

        $validatedRequest = $request->validate([
            'token' => 'required',
            'member' => 'required|exists:members,id',
        ]);

        $member = Member::findOrFail($validatedRequest['member']);
        if ($member->email_verified_at) return $this->error();
        if ($member->email_verification_token_expires_at < Carbon::now()) return $this->error();

        Hash::check($member->email . $member->email_verification_token, $validatedRequest['token']);
        $member->email_verified_at = Carbon::now();
        $member->email_verification_token = null;
        $member->email_verification_token_expires_at = null;
        $member->save();

        $member->notify(new ApplicationEmailVerified());

        return $this->success();
    }

    public function createPasswordResetToken(Request $request): JsonResponse
    {
        $validatedRequest = $request->validate([
            'email' => 'required|exists:members,email|email:rfc,dns',
            'subdomain' => 'required|exists:venues,subdomain',
        ]);

        $venue = Venue::where('subdomain', $validatedRequest['subdomain'])->firstOrFail();

        $member = Member::where('email', $validatedRequest['email'])->first();
        if (!$member) return $this->success();

        $token = Str::random(64);
        $member->password_reset_token = Hash::make($token . $member->email);
        $member->password_reset_token_expires_at = Carbon::now()->addHours(2);
        $member->save();

        $url = 'https://' . $venue->subdomain . '.' . env('MAIN_DOMAIN') . '/password-reset?member=' . $member->id . '&token=' . $token;

        $member->notify(new ApplicationPasswordResetRequest($member, $url));

        return $this->success();
    }


    public function resetPassword(Request $request)
    {
        $validatedRequest = $request->validate([
            'token' => 'required',
            'password' => 'required', 'string', Password::min(8)->mixedCase()->numbers()->symbols()->uncompromised(),
            'email' => 'required|email:rfc,dns',
        ]);

        $member = Member::where('email', $validatedRequest['email'])->first();

        if ($member->password_reset_token_expires_at < Carbon::now()) return $this->error('Token is verlopen');

        if (!$member || !Hash::check($validatedRequest['token'] . $validatedRequest['email'], $member->password_reset_token)) return $this->error(['Email of token onjuist']);

        $member->password = Hash::make($validatedRequest['password']);
        $member->password_reset_token = null;
        $member->password_reset_token_expires_at = null;
        $member->save();

        $member->notify(new ApplicationPasswordResetted($member));

        return $this->success();
    }
}
