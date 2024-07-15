<?php

namespace App\Http\Controllers\Mollie;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\Setting;
use Illuminate\Http\Request;
use Mollie\Api\MollieApiClient;

class MollieWebhookController extends ApiController
{
    public function updatePayments(Request $request)
    {
        if (!$request->has('id')) return $this->error('404');

        $reservation = Reservation::where('payment_id', $request->get('id'))->firstOrFail();
        $venue = $reservation->venue;

        $mollie_key = Setting::where([['venue_id', '=', $venue->id], ['key', '=', 'payment_api_key']])->firstOrFail()->value;
        $mollie = new MollieApiClient();
        $mollie->setApiKey($mollie_key);

        $payment = $mollie->payments->get($reservation->payment_id);

        $reservation->payment_status = $payment->status;
        $reservation->save();

        return $this->success('');

    }

    public function updatePayment(Request $request)
    {
        if($request->id == 'null' || $request->id == null) return $this->error('404');

        $reservation = Reservation::where('payment_id', 'tr_GvSM7TEcmY')->firstOrFail();
        $venue = $reservation->venue;

        $mollie_key = Setting::where([['venue_id', '=', $venue->id], ['key', '=', 'payment_api_key']])->firstOrFail()->value;
        $mollie = new MollieApiClient();
        $mollie->setApiKey($mollie_key);

        $payment = $mollie->payments->get($reservation->payment_id);

        $reservation->payment_status = $payment->status;
        $reservation->save();

        return $this->success();
    }
}
