<?php

namespace App\Http\Controllers\Stripe;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Models\Reservation;
use App\Models\Venue;
use App\WebPayment\PaymentStatus;
use Carbon\Carbon;
use Illuminate\Http\Request;
use function Sentry\captureMessage;

class StripeWebhookController extends ApiController
{
    public function handle(Request $request)
    {
        $payload = json_decode($request->getContent(), true);
        captureMessage($payload['type']);

        switch($payload['type']) {
            case "customer.subscription.updated":
                captureMessage('Customer subscription updated');
                $venue = Venue::where('stripe_subscription_id', $payload['data']['object']['id'])->firstOrFail();

                // Subscription cancelled
                if($payload['data']['object']['canceled_at'] !== null) {
                    captureMessage('Venue Subscription: Subscription geannuleerd');
                    $time = Carbon::parse($payload['data']['object']['canceled_at']);
                    $venue->canceled_at = $time;
                    $venue->stripe_current_period_ends_at = Carbon::createFromTimestamp($payload['data']['object']['current_period_end']);
                    $venue->save();
                }

                // Subscription plan changed
                if($payload['data']['object']['plan']['product'] !== $venue->plan->stripe_product_id || $payload['data']['object']['plan']['id'] !== $venue->plan->stripe_price_id) {
                    captureMessage('Venue Subscription: Subscription plan aangepast');
                    $venue->plan_id = Plan::where([['stripe_product_id', '=', $payload['data']['object']['plan']['product']], ['stripe_price_id', '=', $payload['data']['object']['plan']['id']]])->firstOrFail()->id;
                    $venue->save();
                }

                // Subscription extended paid
                if($payload['data']['object']['canceled_at'] !== null && $payload['data']['object']['cancel_at_period_end'] !== null) {
                    captureMessage('Venue Subscription: Subscription extended');
                    $venue->stripe_current_period_ends_at = Carbon::createFromTimestamp($payload['data']['object']['current_period_end']);
                    $venue->save();
                }

                captureMessage('Webhook ends customer.subscription.updated');
                return;

            // Payment session expired
            case 'checkout.session.expired':
                captureMessage('Checkout session expired');

                if($payload['data']['object']['mode'] === 'payment' && $payload['data']['object']['status'] === 'expired') {
                    captureMessage('Payment expired');
                    $reservation = Reservation::where('payment_id', $payload['data']['object']['id'])->firstOrFail();
                    $reservation->payment_status = PaymentStatus::Expired;
                    $reservation->save();
                }

        }
    }
}
