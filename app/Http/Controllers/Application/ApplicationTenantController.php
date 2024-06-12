<?php

namespace App\Http\Controllers\Application;

use App\Helpers\ReservationHelper;
use App\Http\Controllers\ApiController;
use App\Http\Resources\ProductResource;
use App\Http\Resources\UnitResource;
use App\Http\Resources\VenueResource;
use App\Models\Venue;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ApplicationTenantController extends ApiController
{
    public function getVenueBySubdomain($subdomain)
    {
        $venue = Venue::where('subdomain', $subdomain)->firstOrFail();
        return $this->success(new VenueResource($venue));
    }

    public function getUnitAvailabilityByday(Venue $venue, Request $request)
    {
        $validatedRequest = Validator::make($request->all(), [
            'year' => 'required|numeric|digits_between:4,4',
            'week' => 'required|numeric|min:1|max:52',
            'day' => 'required|numeric|min:1|max:7',
            'date' => 'required',
        ])->validated();

        $year = $validatedRequest['year'];
        $week = $validatedRequest['week'];
        $day = ($validatedRequest['day'] - 1);

        $weeks = $venue->weeks()->where([
            ['year', $year],
            ['week', $week],
        ])->get();

        $availability = [];

        foreach ($weeks as $week) {
            $ranges = $week->template->template[$day]['ranges'];
            $availabilityForWeek = [
                'unit' => [
                    'id' => $week->unit->id,
                    'name' => $week->unit->name,
                    'description' => $week->unit->description,
                ],
                'price' => $week->template->price,
                'interval' => $week->template->interval,
                'week_id' => $week->id,
                'timeblocks' => [],
            ];

            foreach ($ranges as $range) {
                $from = Carbon::create($request->date)->setHour(intval(explode(':', $range['from'])[0]))->setMinute(intval(explode(':', $range['from'])[1]));
                $to = Carbon::create($request->date)->setHour(intval(explode(':', $range['to'])[0]))->setMinute(intval(explode(':', $range['to'])[1]));

                while ($from < $to) {
                    $availabilityForWeek['timeblocks'][] = [
                        'available' => ReservationHelper::checkIfTimeblockIsAvailable($week->unit->id, $from->toDateTimeString(), Carbon::parse($from)->addMinutes($week->template->interval - 1)->toDateTimeString()),
                        'from' => Carbon::parse($from)->toTimeString('minute'),
                        'to' => Carbon::parse($from)->addMinutes($week->template->interval - 1)->toTimeString('minute'),
                        'interval' => $week->template->interval,
                        'price' => $week->template->price,
                        'unit_id' => $week->unit->id,
                    ];
                    $from = Carbon::parse($from)->addMinutes($week->template->interval);
                }
            }
            $availability[] = $availabilityForWeek;
        }
        return $this->success($availability);
    }


    /**
     * Get the available products for reservation
     *
     * @param Venue $venue
     * @return JsonResponse
     */
    public function getAvailableProducts(Venue $venue): JsonResponse
    {
        return $this->success(ProductResource::collection($venue->products->where('is_active', true)));
    }
}
