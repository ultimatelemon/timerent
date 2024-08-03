<?php

namespace App\Http\Middleware;

use App\Models\UserVenue;
use App\Models\Venue;
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
     * @param  \Closure(Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ... $permissions): Response
    {
        $user = Auth::user();
        $venue = Venue::findOrFail(explode('/', $request->getPathInfo())[3]);
        $user = UserVenue::where('user_id' , $user->id)->where('venue_id' , $venue->id)->firstOrFail();

        foreach($permissions as $permission) {
            if((new Permissions($user->role->bitfield))->has($permission)) {
                return $next($request);
            }
        }
        return response('This action is unauthorized by Timerent', 403);
    }
}
