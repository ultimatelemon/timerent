<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProduct;
use App\Http\Requests\StoreUnit;
use App\Http\Resources\UnitResource;
use App\Models\Unit;
use App\Models\Venue;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UnitController extends ApiController
{

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
            $units = $units->where('name', 'ILIKE', "%{$request->q}%");

        $units = $units->paginate(env('POSTS_PER_PACE'));

        return $this->success(
            UnitResource::collection($units),
        );
    }

    /**
     * Store a new resource
     *
     * @param StoreUnit $request
     * @param Venue $venue
     * @return JsonResponse
     */
    public function store(StoreUnit $request, Venue $venue): JsonResponse
    {
        //Todo: Check venue unit limits
        $unit = $venue->units()->create($request->validated());

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
        $unit->update($request->validated());
        return $this->success(['message' => 'Updated successfully']);
    }
}
