<?php

namespace App\Http\Middleware;

use App\Utils\Permissions;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class HasPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ... $permissions): Response
    {
        $user = Auth::user();
        foreach($permissions as $permission) {
            if((new Permissions($user->role->bitfield))->has($permission)) {
                return $next($request);
            }
        }
        return response(null, 403);
    }
}
