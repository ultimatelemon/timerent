<?php

namespace App\Http\Controllers;

use App\Http\Requests\Authentication\LoginRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
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
            return $this->error([__('Wrong username or password')], 400);

        if(!$user->email_verified_at)
            return $this->error([__('Email not verified')], 400);

        $agent = new Agent();
        $sa = $agent->device().', '.$agent->platform().' ('.$agent->browser().')';
        $device_name = $request->get('device_name', $sa);

        $token = $user->createToken($device_name);
        $token->accessToken->expires_at = Carbon::now()->addMinutes(env('TOKEN_EXPIRES_AFTER_MINUTES', 5)+1);
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
}
