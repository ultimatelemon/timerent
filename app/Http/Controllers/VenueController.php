<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Stripe\StripeCustomerController;
use App\Http\Controllers\Stripe\StripeSubscriptionController;
use App\Http\Requests\Venue\StoreVenue;
use App\Http\Resources\VenueResource;
use App\Models\Plan;
use App\Models\UserVenue;
use App\Models\Venue;
use Illuminate\Http\JsonResponse;
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

        UserVenue::create(['user_id' => $request->user()->id, 'venue_id' => $venue->id, 'accepted' => true]);

        if(!(new StripeCustomerController())->create($venue, $request->user())) return $this->error(['']);

        $plan = Plan::findOrFail($request->plan_id);
        $subscription_url = (new StripeSubscriptionController())->create($venue, $plan);

        return $this->success($subscription_url);
    }
}
