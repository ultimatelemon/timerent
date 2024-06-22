<?php

namespace App\Http\Controllers\Stripe;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Venue;
use Stripe\Customer;
use Stripe\Exception\ApiErrorException;
use Stripe\StripeClient;

class StripeCustomerController extends Controller
{

    private StripeClient $client;

    public function __construct()
    {
        $this->client = new StripeClient(env('STRIPE_SECRET'));
    }

    /**
     * Create a new Stripe Customer
     *
     * @param Venue $venue
     * @param User $user
     * @return bool
     * @throws ApiErrorException
     */
    public function create(Venue $venue, User $user): bool
    {
        $customer = $this->client->customers->create([
            'name' => $venue->name,
            'email' => $user->email,
        ]);

        $venue->update(['stripe_customer_id' => $customer->id]);

        return true;
    }

    /**
     * Retrieve the Stripe customer
     *
     * @param Venue $venue
     * @return Customer
     * @throws ApiErrorException
     */
    public function retrieve(Venue $venue): Customer
    {
        return $this->client->customers->retrieve($venue->stripe_customer_id);
    }
}
