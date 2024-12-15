<?php

namespace App\Http\Controllers;

use App\Http\Resources\MonthReportResource;
use App\Models\MonthReport;
use App\Models\Venue;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class MonthReportController extends ApiController
{

    /**
     * Display a listing of the venue month reports
     *
     * @param Request $request
     * @param Venue $venue
     * @return JsonResponse
     */
    public function index(Request $request, Venue $venue): JsonResponse
    {
        $reports = $venue->month_reports();

        $reports = $reports->paginate(env('POSTS_PER_PAGE'));

        return $this->success(
            MonthReportResource::collection($reports),
            collect($reports)->only(['from', 'to', 'total', 'per_page', 'last_page', 'current_page'])->toArray(),
        );
    }

    /**
     * Download the month report
     *
     * @param MonthReport $monthReport
     * @param Venue $venue
     * @return JsonResponse
     */
    public function download(Venue $venue, $monthReportId): JsonResponse
    {
        $monthReport = MonthReport::findOrFail($monthReportId);
        ray($monthReport);
        $keys = [
            'month',
            'year',
            'revenue_high',
            'revenue_low',
            'tax_amount_high',
            'tax_amount_low',
            'customer_count',
            'reservation_count',
            'period_from',
            'period_till',
            'available_at'
        ];

        $data = $monthReport->only($keys);
        $pdf = Pdf::loadView('pdf.monthly-report', compact('venue', 'data'));

        return $this->success($pdf->outputHtml());
    }

    /**
     * Generate monthreports for venues
     *
     * @param Venue $venue
     * @return void
     */
    public function generate(Venue $venue): void
    {
        $monthToGenerate = 12;
        $yearToGenerate = 2024;
//        $monthToGenerate = now()->month === 1 ? 12 : now()->month - 1;
//        $yearToGenerate = $monthToGenerate === 12 ? now()->year - 1 : now()->year;
        $from = Carbon::now()->setMonth($monthToGenerate)->setYear($yearToGenerate)->startOfMonth()->setTime(0, 0, 0)->format('Y-m-d H:i:s');
        $to = Carbon::now()->setMonth($monthToGenerate)->setYear($yearToGenerate)->endOfMonth()->setTime(23, 59, 59)->format('Y-m-d H:i:s');

        $reservations = $venue->reservations()->where('created_at', '>', $from)->where('created_at', '<', $to)->where('payment_status', 'paid')->get();
        $customer_count = $venue->reservations()->where('payment_status', 'paid')->distinct()->count('email');
        $revenueHigh = collect($reservations->map(function ($reservation) {return $reservation->revenue_high;}))->sum();
        $revenueLow = collect($reservations->map(function ($reservation) {return $reservation->revenue_low;}))->sum();
        $tax_low = collect($reservations->map(function ($reservation) {return $reservation->tax_low;}))->sum();
        $tax_high = collect($reservations->map(function ($reservation) {return $reservation->tax_high;}))->sum();

        $monthReport = MonthReport::create([
            'venue_id' => $venue->id,
            'month' => $monthToGenerate,
            'year' => $yearToGenerate,
            'period_from' => $from,
            'period_to' => $to,
            'reservation_count' => $reservations->count(),
            'customer_count' => $customer_count,
            'revenue_high' => $revenueHigh,
            'revenue_low' => $revenueLow,
            'tax_amount_high' => $tax_high,
            'tax_amount_low' => $tax_low,
            'available_at' => now(),
        ]);

        return;
    }
}
