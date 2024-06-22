<?php

namespace App\Http\Controllers\Stripe;

use App\Http\Controllers\Controller;
use App\Models\Venue;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Stripe\Exception\ApiErrorException;
use Stripe\StripeClient;

class StripeCallbackController extends Controller
{

    private StripeClient $client;

    public function __construct()
    {
        $this->client = new StripeClient(env('STRIPE_SECRET'));
    }

    /**
     * Handle a success payment for subscription
     * @throws ApiErrorException
     */
    public function success(Request $request)
    {
        $session_id = $request->session_id;

        $session = $this->client->checkout->sessions->retrieve($session_id, ['expand' => ['subscription']]);
        $period_end = Carbon::createFromTimestamp($session->subscription->current_period_end)->toDateTimeString();

        $venue = Venue::where('stripe_subscription_id', $session->id)->firstOrFail();
        $venue->stripe_subscription_id = $session->subscription->id;
        $venue->stripe_current_period_ends_at = $period_end;
        $venue->save();

        return view('callback.success');
    }
}
