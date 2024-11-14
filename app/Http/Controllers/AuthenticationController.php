<?php

namespace App\Http\Controllers;

use App\Http\Requests\Authentication\LoginRequest;
use App\Http\Requests\Authentication\StoreUser;
use App\Models\User;
use App\Notifications\Auth\EmailVerified;
use App\Notifications\Auth\PasswordResetRequest;
use App\Notifications\Auth\PasswordResetted;
use Carbon\Carbon;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;
use Jenssegers\Agent\Agent;

class AuthenticationController extends ApiController
{
    /**
     * Create a new token
     *
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function createToken(LoginRequest $request): JsonResponse
    {
        $validatedRequest = $request->validated();
        $user = User::where('email', strtolower($validatedRequest['email']))->first();

        if(!$user || !Hash::check($request->password, $user->password))
            return $this->error([__('Wrong email or password')], 400);

        if(!$user->email_verified_at)
            return $this->error(['email_verification' => __('Email not verified')], 400);

        $agent = new Agent();
        $sa = $agent->device().', '.$agent->platform().' ('.$agent->browser().')';
        $device_name = $request->get('device_name', $sa);

        $token = $user->createToken($device_name);
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
        if($request->has('id') && $request->get('id') != null) {
            $token = $request->user()->tokens()->where('id', $request->get('id'))->firstOrFail();
            $token->delete();
        } else {
            $request->user()->currentAccessToken()->delete();
        }
        return $this->success();
    }

    /**
     * Store a new user
     *
     * @param StoreUser $request
     * @return JsonResponse
     */
    public function createUser(StoreUser $request): JsonResponse
    {
        $validatedRequest = $request->validated();
        $user = new User();
        $user->password = Hash::make($validatedRequest['password']);
        $user->email = strtolower($validatedRequest['email']);
        $user->name = $validatedRequest['name'];
        $user->role_id = '509ab95a-9dbc-4857-a142-c3a1fa9a9812';
        $user->email_verification_token = Str::random(64);
        $user->email_verification_token_expires_at = Carbon::now()->addHours(2);
        $user->save();

        event(new Registered($user));

        return $this->success();
    }


    /**
     * Verify email after registration
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function verifyEmail(Request $request): JsonResponse
    {
        $validatedRequest = $request->validate([
            'token' => 'required',
            'user' => 'required|exists:users,id',
        ]);

        $user = User::findOrFail($validatedRequest['user']);
        ray($user);
        if($user->email_verified_at) return $this->error();
        if($user->email_verification_token_expires_at < Carbon::now()) return $this->error();

        Hash::check($user->email . $user->email_verification_token, $validatedRequest['token']);
        $user->email_verified_at = Carbon::now();
        $user->email_verification_token = null;
        $user->email_verification_token_expires_at = null;
        $user->save();

        $user->notify(new EmailVerified($user));

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
            'email' => 'required|exists:users,email',
        ]);

        $user = User::where('email', $validatedRequest['email'])->firstOrFail();
        if($user->email_verified_at) return $this->error();

        $user->email_verification_token = Str::random(64);
        $user->email_verification_token_expires_at = Carbon::now()->addHours(2);
        $user->save();

        event(new Registered($user));

        return $this->success();
    }

    public function createPasswordResetToken(Request $request): JsonResponse
    {
        $validatedRequest = $request->validate([
            'email' => 'required|exists:users,email|email:rfc,dns',
        ]);

        $user = User::where('email', $request->email)->first();
        if(!$user) return $this->error();

        $token = Str::random(64);
        $user->password_reset_token = Hash::make($token . $user->email);
        $user->password_reset_token_expires_at = Carbon::now()->addHours(2);
        $user->save();

        $url = env('APP_URL') . '/password-reset?user=' . $user->id . '&token=' . $token;

        $user->notify(new PasswordResetRequest($user, $url));

        return $this->success();
    }

    public function resetPassword(Request $request)
    {
        $validatedRequest = $request->validate([
            'token' => 'required',
            'password' => 'required', 'string', Password::min(8)->mixedCase()->numbers()->symbols()->uncompromised(),
            'email' => 'required|email:rfc,dns',
        ]);

        $user = User::where('email', $validatedRequest['email'])->first();
        if (!$user || $user->password_reset_token_expires_at < Carbon::now()) return $this->error($user->password_reset_token_expires_at);
        if (!Hash::check($validatedRequest['token'] . $validatedRequest['email'], $user->password_reset_token)) return $this->error(['Email of token onjuist']);

        $user->password = Hash::make($validatedRequest['password']);
        $user->password_reset_token = null;
        $user->password_reset_token_expires_at = null;
        $user->save();

        $user->notify(new PasswordResetted($user));

        return $this->success();
    }
}
