<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;
use Symfony\Component\HttpFoundation\Response;

class RetrieveMember
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->bearerToken() !== null && $request->bearerToken() !== 'null') {
            $token = explode('|', $request->bearerToken())[1];
            $tokkie = PersonalAccessToken::where('token', hash('sha256', $token))->first();
            $request->merge(['member' => $tokkie->tokenable]);
            return $next($request);
        }

        return $next($request);
    }
}
