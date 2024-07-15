<?php

namespace App\Http\Controllers\Mollie;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\Setting;
use Illuminate\Support\Facades\Request;
use Mollie\Api\MollieApiClient;

class MollieWebhookController extends ApiController
{
    public function updatePayment(Request $request): \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\View|\Illuminate\Http\JsonResponse
    {
        if(!$request->has('id')) return $this->error();

        $reservation = Reservation::where('payment_id', $request->get('id'))->firstOrFail();
        $venue = $reservation->venue;

        $mollie_key = Setting::where([['venue_id', '=', $venue->id], ['key', '=', 'payment_api_key']])->firstOrFail()->value;
        $mollie = new MollieApiClient();
        $mollie->setApiKey($mollie_key);

        $payment = $mollie->payments->get($reservation->payment_id);

        $reservation->payment_status = $payment->status;
        $reservation->save();

        return view('application.callback.success');

    }
}
