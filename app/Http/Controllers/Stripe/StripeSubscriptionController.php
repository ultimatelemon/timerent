<?php

namespace App\Http\Controllers\Stripe;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Models\Venue;
use Stripe\Exception\ApiErrorException;
use Stripe\StripeClient;

class StripeSubscriptionController extends ApiController
{

    private StripeClient $client;

    public function __construct()
    {
        $this->client = new StripeClient(env('STRIPE_SECRET'));
    }

    /**
     * Create a new subscription
     *
     * @param Venue $venue
     * @param Plan $plan
     * @return string|null
     * @throws ApiErrorException
     */
    public function create(Venue $venue, Plan $plan): ?string
    {
        $session = $this->client->checkout->sessions->create([
            'customer' => $venue->stripe_customer_id,
            'payment_method_types' => ['ideal'],
            'line_items' => [[
                'price' => $plan->stripe_price_id,
                'quantity' => 1,
                'tax_rates' => [env('STRIPE_TAX_RATE')],
            ]],
            'allow_promotion_codes' => true,
            'mode' => 'subscription',
            'success_url' => env('STRIPE_SUCCESS_URL') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => env('STRIPE_CANCEL_URL'),
        ]);
        $venue->update(['stripe_subscription_id' => $session->id]);

        return $session->url;
    }


    /**
     * Retrieve a subscription
     *
     * @param Venue $venue
     * @return void
     */
    public function retrieve(Venue $venue)
    {

    }


    /**
     * Cancel a subscription
     *
     * @param Venue $venue
     * @return void
     */
    public function cancel(Venue $venue)
    {

    }
}
