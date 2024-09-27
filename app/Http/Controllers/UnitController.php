<?php

namespace App\Http\Controllers;

use App\Http\Requests\Venue\StoreUnit;
use App\Http\Resources\UnitResource;
use App\Models\Unit;
use App\Models\Venue;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Stripe\Exception\ApiErrorException;
use Stripe\StripeClient;

class UnitController extends ApiController implements HasMiddleware
{

    public static function middleware(): array
    {
        return [
                new Middleware('unitLimit', only: ['store']),
                new Middleware('hasPermissions:VIEW_UNITS', only: ['index', 'show']),
                new Middleware('hasPermissions:MANAGE_UNITS', only: ['store', 'update', 'destroy']),
        ];
    }

    /**
     * Display a listing of the resource
     *
     * @param Request $request
     * @param Venue $venue
     * @return JsonResponse
     */
    public function index(Request $request, Venue $venue): JsonResponse
    {
        $units = $venue->units();

        if($request->has('q'))
            $units = $units->where('name', 'ILIKE', "%{$request->q}%")
                ->orWhere('description', 'ILIKE', "%{$request->q}%");

        $units = $units->paginate(env('POSTS_PER_PAGE'));

        return $this->success(
            UnitResource::collection($units),
            collect($units)->only(['from', 'to', 'total', 'per_page', 'last_page', 'current_page'])->toArray(),
        );
    }

    /**
     * Store a new resource
     *
     * @param StoreUnit $request
     * @param Venue $venue
     * @return JsonResponse
     * @throws ApiErrorException
     */
    public function store(StoreUnit $request, Venue $venue): JsonResponse
    {
        //Todo: Check venue unit limits
        $unit = $venue->units()->create($request->validated());

        if($venue->units()->count() > $venue->plan->unit_limit) {
            $client = new StripeClient(env('STRIPE_SECRET'));
            $item = $client->invoiceItems->create([
                'customer' => $venue->stripe_customer_id,
                'price' => env('EXTRA_UNIT_PRICE_ID'),
                'currency' => 'eur',
                'description' => 'Extra unit buiten abonnement',
            ]);
        }

        return $this->success(['message' => 'Stored successfully']);
    }

    /**
     * Show the specific resource
     *
     * @param Venue $venue
     * @param Unit $unit
     * @return JsonResponse
     */
    public function show(Venue $venue, Unit $unit): JsonResponse
    {
        return $this->success(new UnitResource($unit));
    }

    /**
     * Update the specific resource
     *
     * @param StoreUnit $request
     * @param Venue $venue
     * @param Unit $unit
     * @return JsonResponse
     */
    public function update(StoreUnit $request, Venue $venue, Unit $unit): JsonResponse
    {
        $unit->update($request->except('groups'));
        $unit->groups()->sync($request->groups);
        return $this->success(['message' => 'Updated successfully']);
    }

    /**
     * Delete the specific resource
     *
     * @param Venue $venue
     * @param Unit $unit
     * @return JsonResponse
     */
    public function destroy(Venue $venue, Unit $unit): JsonResponse
    {
        $unit->delete();
        return $this->success();
    }
}
