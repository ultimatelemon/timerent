<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Stripe\StripeCustomerController;
use App\Http\Controllers\Stripe\StripeSubscriptionController;
use App\Http\Requests\Venue\StoreVenue;
use App\Http\Resources\VenueResource;
use App\Models\Plan;
use App\Models\UserVenue;
use App\Models\Venue;
use Database\Seeders\SettingSeeder;
use Illuminate\Http\JsonResponse;
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

        UserVenue::create(['user_id' => $request->user()->id, 'venue_id' => $venue->id, 'accepted' => true]);

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
}
