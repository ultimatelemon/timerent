<?php

namespace App\Http\Controllers\Application;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Venue;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ApplicationInvoiceController extends ApiController
{
    public function download(Request $request, Invoice $invoice)
    {
        if(!$request->member->reservations()->where('id', $invoice->reservation_id)->first()) return $this->error();

        $keys = ['name', 'address', 'phone_number_support', 'postal_code', 'city', 'coc_number', 'tax_number'];
        $venue = Venue::findOrFail($invoice->venue_id);
        $business = $venue->only($keys);
        $pdf = Pdf::loadView('application.pdf.invoice', compact('invoice', 'business'));

        return $this->success($pdf->outputHtml());
    }
}
