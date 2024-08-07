<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Stripe\StripeConnectController;
use App\Http\Controllers\Stripe\StripeCustomerController;
use App\Http\Controllers\Stripe\StripeSubscriptionController;
use App\Http\Requests\Venue\StoreVenue;
use App\Http\Resources\VenueResource;
use App\Models\Plan;
use App\Models\User;
use App\Models\UserVenue;
use App\Models\Venue;
use Database\Seeders\SettingSeeder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Stripe\Exception\ApiErrorException;

class VenueController extends ApiController
{

    /**
     * Store a new venue
     *
     * @param StoreVenue $request
     * @return JsonResponse
     * @throws ApiErrorException
     */
    public function store(StoreVenue $request): JsonResponse
    {
        $venue = Venue::create($request->all());

        UserVenue::create(['user_id' => $request->user()->id, 'venue_id' => $venue->id, 'owner' => true, 'role_id' => '6e488921-9675-4e5e-b7b5-8b6acd1ecd18']);

        if (!(new StripeCustomerController())->create($venue, $request->user())) return $this->error(['']);

        $plan = Plan::findOrFail($request->plan_id);
        $subscription_url = (new StripeSubscriptionController())->create($venue, $plan);

        Artisan::call('venue:seed', [
            'venue_id' => $venue->id,
        ]);

        return $this->success($subscription_url);
    }


    /**
     * Display venue
     *
     * @param Venue $venue
     * @return JsonResponse
     */
    public function show(Venue $venue): JsonResponse
    {
        return $this->success(new VenueResource($venue));
    }

    /**
     * Setup Timerent Payments
     *
     * @param Venue $venue
     * @return JsonResponse
     * @throws ApiErrorException
     */
    public function setupTimerentPayments(Venue $venue): JsonResponse
    {
        $currentAccount = $venue->stripe_connect_id;

        $connectedAccount = (new StripeConnectController())->storeConnectedAccount($venue);
        $venue->update(['stripe_connect_id' => $connectedAccount->id]);
        $onboardingUrl = (new StripeConnectController())->accountLink($venue);

        if($currentAccount) (new StripeConnectController())->deleteConnectedAccount($currentAccount, $venue);

        return $this->success($onboardingUrl);
    }
}
