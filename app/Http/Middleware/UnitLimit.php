<?php

namespace App\Http\Middleware;

use App\Models\Venue;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UnitLimit
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
//        $venue = Venue::findOrFail(explode('/', $request->getPathInfo())[3]);
//
//        if(($venue->units->count() + 1) > $venue->plan->unit_limit)

        return $next($request);
    }
}
