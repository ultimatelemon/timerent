<?php

namespace App\Http\Controllers;

use App\Http\Resources\InvoiceResource;
use App\Models\Invoice;
use App\Models\Member;
use App\Models\Reservation;
use App\Models\Setting;
use App\Models\Venue;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class InvoiceController extends ApiController
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
        $invoices = $venue->invoices();

        if($request->has('q'))
            $invoices = $invoices->where('id', 'ILIKE', "%{$request->q}%")
                ->orWhere('email', 'ILIKE', "%{$request->q}%");

        $invoices = $invoices->orderBy('created_at', 'asc');

        $invoices = $invoices->paginate(env('POSTS_PER_PAGE'));

        return $this->success(
            InvoiceResource::collection($invoices),
            collect($invoices)->only(['from', 'to', 'total', 'per_page', 'last_page', 'current_page'])->toArray(),
        );
    }

    /**
     * Store new invoice based on reservation
     *
     * @param Reservation $reservation
     * @return JsonResponse
     */
    public function store(Reservation $reservation): JsonResponse
    {
        $invoice = new Invoice;
        $invoice->name = $reservation->name;
        $invoice->email = $reservation->email;
        $invoice->phone_number = $reservation->phone_number;
        $invoice->payment_amount = $reservation->payment_amount;
        $invoice->tax_low = $reservation->tax_low;
        $invoice->tax_high = $reservation->tax_high;
        $invoice->payment_status = $reservation->payment_status;
        $invoice->sent_at = Carbon::now();
        $invoice->paid_at = Carbon::now();
        $invoice->venue_id = $reservation->venue_id;
        $invoice->save();

        return $this->success();
    }

    public function download(Venue $venue, Invoice $invoice): JsonResponse
    {
        $keys = ['name', 'address', 'postal_code', 'city', 'phone_number_support', 'coc_number', 'tax_number'];
        $business = Setting::whereIn('key', $keys)->where('venue_id', $invoice->venue_id)->get()->pluck('value', 'key')->toArray();
        $pdf = Pdf::loadView('application.pdf.invoice', compact('invoice', 'business'));

        return $this->success($pdf->outputHtml());
    }

}
