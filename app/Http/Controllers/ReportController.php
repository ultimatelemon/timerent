<?php

namespace App\Http\Controllers;

use App\Models\Venue;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReportController extends ApiController
{
    /**
     * Display the report index
     *
     * @param Request $request
     * @param Venue $venue
     * @return JsonResponse
     */
    public function index(Request $request, Venue $venue): JsonResponse
    {
        $validatedRequest = $request->validate([
            'from' => 'required|date',
            'to' => 'required|date',
        ]);

        // TODO: Kijken naar week ipv from en to voor charts;

        $from = Carbon::parse($validatedRequest['from'])->setHour(0)->setMinute(0)->setSecond(0);
        $to = Carbon::parse($validatedRequest['to'])->setHour(23)->setMinute(59)->setSecond(59);

        $reservations = $venue->reservations->where('created_at', '>=', $from)->where('created_at', '<=', $to)->where('payment_status', 'paid');
        $revenue = collect($reservations->map(function ($reservation) {return $reservation->payment_amount;}))->sum();
        $tax_low = collect($reservations->map(function ($reservation) {return $reservation->tax_low;}))->sum();
        $tax_high = collect($reservations->map(function ($reservation) {return $reservation->tax_high;}))->sum();
        $customer_count = $venue->reservations()->where('payment_status', 'paid')->distinct()->count('email');

        $charts = [];
        $charts['reservations'] = [
            'options' => [
                'chart' => [
                    'id' => 'reservations'
                ],
                'xaxis' => [
                    'categories' => ['Ma', 'Di', 'Wo', 'Do', 'Vr', 'Za', 'Zo']
                ]
            ],

            'series' => [
                [
                    'name' => 'reservations',
                    'data' => [CarbonInterface::MONDAY, 40, 45, 50, 49, 60, 70, 91]
                ]
            ]
        ];

        return $this->success([
            'reservations_count' => $reservations->count(),
            'revenue_incl' => $revenue,
            'revenue_excl' => $revenue - ($tax_low + $tax_high),
            'tax_low' => $tax_low,
            'tax_high' => $tax_high,
            'customer_count' => $customer_count,
            'charts' => $charts,
        ]);
    }
}
