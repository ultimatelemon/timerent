<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Venue;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StatisticsController extends ApiController
{
    public function index(Venue $venue, Request $request): JsonResponse
    {
        $reservations = Reservation::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->where('payment_status', 'paid')->count();
        $reservations_prev_week = Reservation::whereBetween('created_at', [Carbon::now()->subWeek()->startOfWeek(), Carbon::now()->subWeek()->endOfWeek()])->where('payment_status', 'paid')->count();

        $revenue = Reservation::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->where('payment_status', 'paid')->sum('payment_amount');
        $revenue_prev_week = Reservation::whereBetween('created_at', [Carbon::now()->subWeek()->startOfWeek(), Carbon::now()->subWeek()->endOfWeek()])->where('payment_status', 'paid')->sum('payment_amount');

        return $this->success([
            'revenue' => [
                'now' => $revenue,
                'previous_week' => $revenue_prev_week
            ],
            'reservations' => [
                'now' => $reservations,
                'previous_week' => $reservations_prev_week
            ],
            'average_spending' => [
                'now' => ($revenue != 0 && $reservations != 0 ? $revenue / $reservations : 0),
                'previous_week' => ($revenue_prev_week != 0 && $reservations_prev_week != 0 ? $revenue_prev_week / $reservations_prev_week : 0)
            ],

        ]);
    }
}
