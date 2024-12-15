<?php

namespace App\Http\Controllers;

use App\Http\Requests\Venue\StoreWeek;
use App\Http\Resources\WeekResource;
use App\Models\Template;
use App\Models\Unit;
use App\Models\Venue;
use App\Models\Week;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Validator;

class WeekController extends ApiController implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('hasPermissions:VIEW_AGENDA', only: ['index']),
            new Middleware('hasPermissions:VIEW_INVOICES', only: ['updateOrCreate']),
        ];
    }

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
            if(!$week){
                $template = Template::findOrFail($validatedRequest['template_id']);
                $week = new Week;
                $week->fill($validatedRequest);
                $week->template_name = $template->name;
                $week->template = $template->template;
                $week->interval = $template->interval;
                $week->price = $template->price;
                $week->save();
            }
            return $this->success($week);
        }

        if($week) $week->forceDelete();
        return $this->success();

    }

    public function update(Venue $venue, Week $week, Request $request): JsonResponse
    {
        $validatedRequest = Validator::make($request->all(), [
            'year' => 'required|numeric|digits:4',
            'week' => 'required|numeric|digits_between:1,52',
            'unit_id' => 'required|exists:units,id',
            'template_name' => 'required|string|max:120',
            'interval' => 'required|numeric|min:1|max:60',
            'template' => 'required',
            'price' => 'required|numeric|min:1|max:9999999'
        ]);

        $week->update($validatedRequest->validated());
        $week->update(['changed_from_origin' => true]);

        return $this->success();
    }

    /**
     * Display the week.
     *
     * @param Venue $venue
     * @param Week $week
     * @return JsonResponse
     */
    public function show(Venue $venue, Week $week): JsonResponse
    {
        return $this->success(new WeekResource($week));
    }
}
