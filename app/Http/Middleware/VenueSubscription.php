<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VenueSubscription
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        // TODO: Fix venue subscription middleware
        return $next($request);
    }
}
