<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\ApiController;
use App\Http\Resources\UserVenueResource;
use App\Http\Resources\VenueResource;
use App\Models\User;
use App\Models\Venue;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserVenueController extends ApiController
{
    public function index(Request $request, User $user): JsonResponse
    {
        $user_venues = $user->user_venues()->with('venue');
        if($request->has('q'))
            $user_venues = $user_venues->where('user_venues.name', 'ILIKE', "%{$request->q}%");

        $user_venues = $user_venues->paginate(env('POST_PER_PAGE'));

        return $this->success(UserVenueResource::collection($user_venues), null, $user_venues->toArray());
    }

    public function show(Request $request, User $user, Venue $venue): JsonResponse
    {
        return $this->success(new VenueResource($venue));
    }

    public function update(Request $request, User $user, Venue $venue): JsonResponse
    {
        $validatedRequest = Validator::make($request->all(), [
            'name' => 'required|string|max:32',
            'description' => 'nullable|string|max:200',
            'address' => 'nullable|string',
            'postal_code' => 'nullable|string',
            'city' => 'nullable|string',
            'email' => 'nullable|email',
            'phone' => 'nullable|string',
            'coc_number' => 'nullable|string',
            'tax_number' => 'nullable|string',
            'bank_number' => 'nullable|string',

            'subdomain' => 'required|string|lowercase|max:32|regex:/^[a-z\-]+$/|unique:venues,subdomain,'.$venue->id,

            'avatar_id' => 'nullable|uuid|exists:files,id',
            'cover_id' => 'nullable|uuid|exists:files,id',
            'receipt_logo_id' => 'nullable|uuid|exists:files,id',
        ]);

        $venue->update($validatedRequest->validated());
        return $this->success(new VenueResource($venue));
    }

    public function openCustomerPortal(Request $request, User $user, Venue $venue): JsonResponse
    {
        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
        $session = $stripe->billingPortal->sessions->create([
            'customer' => $venue->stripe_customer_id,
            'return_url' => $request->return_url,
        ]);

        return $this->success($session);
    }
}
