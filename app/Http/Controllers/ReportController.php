<?php

namespace App\Http\Controllers;

use App\Models\Venue;
use Carbon\Carbon;
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


        $from = Carbon::parse($validatedRequest['from']);
        $to = Carbon::parse($validatedRequest['to']);

        $reservations = $venue->reservations->where('created_at', '>=', $from)->where('created_at', '<=', $to)->where('payment_status', 'paid');
        $revenue = collect($reservations->map(function ($reservation) {return $reservation->payment_amount;}))->sum();
        $tax_low = collect($reservations->map(function ($reservation) {return $reservation->tax_high;}))->sum();
        $tax_high = collect($reservations->map(function ($reservation) {return $reservation->tax_low;}))->sum();
        $customer_count = $venue->reservations()->where('payment_status', 'paid')->distinct()->count('email');

        return $this->success([
            'reservations_count' => $reservations->count(),
            'revenue_incl' => $revenue,
            'revenue_excl' => $revenue - ($tax_low + $tax_high),
            'tax_low' => $tax_low,
            'tax_high' => $tax_high,
            'customer_count' => $customer_count,
        ]);
    }
}
