<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreWeek;
use App\Http\Resources\WeekResource;
use App\Models\Template;
use App\Models\Unit;
use App\Models\Venue;
use App\Models\Week;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WeekController extends ApiController
{
    /**
     * Display a listing of the resource
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $weeks = Week::all();
        return $this->success(WeekResource::collection($weeks));
    }

    /**
     * Update the current week or create a new one.
     *
     * @param StoreWeek $request
     * @param Venue $venue
     * @return JsonResponse
     */
    public function updateOrCreate(StoreWeek $request, Venue $venue): JsonResponse
    {
        $validatedRequest = $request->validated();
        $validatedRequest['venue_id'] = $venue->id;
        $unit = Unit::findOrFail($validatedRequest['unit_id']);
        $week = Week::where([
            ['unit_id', '=', $unit->id],
            ['year', '=', $validatedRequest['year']],
            ['week', '=', $validatedRequest['week']],
        ])->first();

        if($validatedRequest['template_id'] !== null) {
            if($week) $week->update($validatedRequest);
            $newWeek = Week::create($validatedRequest);
            return $this->success($newWeek);
        }

        if($week) $week->forceDelete();
        return $this->success();

    }
}
