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
use Stripe\StripeClient;
use function Sentry\captureMessage;

class StripeWebhookController extends ApiController
{
    public function handle(Request $request): void
    {
        $payload = json_decode($request->getContent(), true);
        captureMessage($payload['type']);

        switch ($payload['type']) {
            case "customer.subscription.updated":
                captureMessage('Customer subscription updated');
                http_response_code(200);

                $venue = Venue::where('stripe_subscription_id', $payload['data']['object']['id'])->firstOrFail();

                // Subscription cancelled
                if ($payload['data']['object']['canceled_at'] !== null) {
                    captureMessage('Venue Subscription: Subscription geannuleerd');
                    $time = Carbon::parse($payload['data']['object']['canceled_at']);
                    $venue->canceled_at = $time;
                    $venue->stripe_current_period_ends_at = Carbon::createFromTimestamp($payload['data']['object']['current_period_end']);
                    $venue->save();
                }

                // Subscription plan changed
                if ($payload['data']['object']['plan']['product'] !== $venue->plan->stripe_product_id || $payload['data']['object']['plan']['id'] !== $venue->plan->stripe_price_id) {
                    captureMessage('Venue Subscription: Subscription plan aangepast');
                    $venue->plan_id = Plan::where([['stripe_product_id', '=', $payload['data']['object']['plan']['product']], ['stripe_price_id', '=', $payload['data']['object']['plan']['id']]])->firstOrFail()->id;
                    $venue->save();
                }

                // Subscription extended paid
//                captureMessage($payload['data']['object']['canceled_at'] ?? 'Geen annulering');
//                captureMessage($payload['data']['object']['cancel_at_period_end'] ?? 'Geen cancel op period end');
//                captureMessage($payload['data']['object']['current_period_end'] ?? 'Current periode end');

                $cancelat = 'Cancelat is null';
                if($payload['data']['object']['canceled_at'] !== null) {
                    $cancelat = $payload['data']['object']['canceled_at'];
                }
                captureMessage("cancelat " . $cancelat);

                $cancelperiodends = 'Cancel period ends is false';
                if($payload['data']['object']['canceled_at'] !== null) {
                    $cancelperiodends = $payload['data']['object']['canceled_at'];
                }
                captureMessage("ends " . $cancelperiodends);

                if ($payload['data']['object']['canceled_at'] === 'null' && $payload['data']['object']['cancel_at_period_end'] === 'false') {
                    captureMessage('Venue Subscription: Subscription extended');
                    $venue->stripe_current_period_ends_at = Carbon::createFromTimestamp($payload['data']['object']['current_period_end']);
                    $venue->save();

                    if ($venue->units()->count() > $venue->plan->unit_limit) {
                        $client = new StripeClient(env('STRIPE_SECRET'));
                        $item = $client->invoiceItems->create([
                            'quantity' => ($venue->units()->count() - $venue->plan->unit_limit),
                            'customer' => $venue->stripe_customer_id,
                            'price' => env('EXTRA_UNIT_PRICE_ID'),
                            'currency' => 'eur',
                            'description' => 'Extra unit buiten abonnement',
                        ]);
                    }
                }

                captureMessage('Webhook ends customer.subscription.updated');
                break;

            // Payment session expired
            case 'checkout.session.expired':
                captureMessage('Checkout session expired');

                if ($payload['data']['object']['mode'] === 'payment' && $payload['data']['object']['status'] === 'expired') {
                    captureMessage('Payment expired');
                    $reservation = Reservation::where('payment_id', $payload['data']['object']['id'])->firstOrFail();
                    $reservation->payment_status = PaymentStatus::Expired;
                    $reservation->save();
                }
                break;
        }
    }
}
