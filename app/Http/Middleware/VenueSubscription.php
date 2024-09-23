<?php

namespace App\Http\Middleware;

use App\Models\Venue;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VenueSubscription
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(str_contains($request->getPathInfo(), 'store')) {
            $venue = Venue::findOrFail(explode('/', $request->getPathInfo())[2]);

            if ($venue->stripe_current_period_ends_at <= Carbon::now()) return redirect()->route('index');
        }

        return $next($request);
    }
}
