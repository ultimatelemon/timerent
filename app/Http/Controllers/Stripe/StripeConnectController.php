<?php

namespace App\Http\Controllers\Stripe;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\Venue;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Stripe\Account;
use Stripe\Exception\ApiErrorException;
use Stripe\StripeClient;

class StripeConnectController extends ApiController
{

    /**
     * The StripeClient instance
     *
     * @var StripeClient
     */
    private StripeClient $client;

    /**
     * Construct the Stripe API
     */
    public function __construct()
    {
        $this->client = new StripeClient(env('STRIPE_SECRET'));
    }


    /**
     * Set up a Stripe Connect account
     *
     * @param Venue $venue
     * @return Account
     * @throws ApiErrorException
     */
    public function storeConnectedAccount(Venue $venue)
    {
        $account = $this->client->accounts->create([
            'type' => 'standard',
            'country' => 'NL',
            'email' => Auth::user()->email,
            'settings' => [
                'payouts' => [
                    'schedule' => [
                        'interval' => 'monthly',
                        'monthly_anchor' => 1,
                    ],
                    'statement_descriptor' => 'Timerent Payouts',
                ]
            ]
        ]);

        return $account;
    }

    /**
     * Delete Stripe Connect account and unlink from Venue
     *
     * @param string $id
     * @param Venue $venue
     * @return bool
     * @throws ApiErrorException
     */
    public function deleteConnectedAccount(string $id, Venue $venue): bool
    {
        $delete = $this->client->accounts->delete($id);
        if($venue->stripe_connect_id === $id) $venue->update(['stripe_connect_id' => null]);
        return $delete->isDeleted();
    }

    /**
     * Link the account and send onboarding URL
     *
     * @param Venue $venue
     * @return string
     * @throws ApiErrorException
     */
    public function accountLink(Venue $venue): string
    {
        $accountLink = $this->client->accountLinks->create([
            'account' => $venue->stripe_connect_id,
            'refresh_url' => env('APP_URL'),
            'return_url' => env('APP_URL') . '/timerentpayments/success?v=' . $venue->id,
            'type' => 'account_onboarding',
        ]);
        return $accountLink->url;
    }

    /**
     * Check if the required details are submitted and update the Venue
     *
     * @param Venue $venue
     * @return JsonResponse
     * @throws ApiErrorException
     */
    public function checkOnboardedAndUpdateVenue(Venue $venue): JsonResponse
    {
        $account = $this->client->accounts->retrieve($venue->stripe_connect_id);
        if(!$account->details_submitted) return $this->error(['Details not submitted']);

        $venue->stripe_connect_onboarded = true;
        $venue->save();

        return $this->success();
    }
}
