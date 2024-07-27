<?php

namespace App\Http\Controllers\Mollie;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Reservation;
use App\Models\Setting;
use App\Notifications\ReservationConfirmation;
use App\Notifications\Traits\EmailNotifiable;
use App\WebPayment\Mollie\MolliePaymentClient;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\View\View;
use Mollie\Api\MollieApiClient;

class MollieWebhookController extends ApiController
{
//    public function updatePayments(Request $request)
//    {
//        if (!$request->has('id')) return $this->error('404');
//
//        $reservation = Reservation::where('payment_id', $request->get('id'))->firstOrFail();
//        $venue = $reservation->venue;
//
//        $mollie_key = Setting::where([['venue_id', '=', $venue->id], ['key', '=', 'payment_api_key']])->firstOrFail()->value;
//        $mollie = new MollieApiClient();
//        $mollie->setApiKey($mollie_key);
//
//        $payment = $mollie->payments->get($reservation->payment_id);
//
//        $reservation->payment_status = $payment->status;
//        $reservation->save();
//
//        return $this->success('');
//
//    }

    public function updatePayment(Reservation $reservation): JsonResponse|View
    {
        try {
            $venue = $reservation->venue;

            $mollie_key = Setting::where([['venue_id', '=', $venue->id], ['key', '=', 'payment_api_key']])->firstOrFail()->value;
            $client = new MolliePaymentClient(Crypt::decrypt($mollie_key));

            $payment = $client->getPayment($reservation->payment_id);

            $reservation->payment_status = $payment->getStatus()->value;
            $reservation->save();

            $invoice = Invoice::where('reservation_id', $reservation->id)->firstOrFail();
            $invoice->paid_at = Carbon::now();
            $invoice->save();

            // TODO: Mail confirmation
            $emailNotifiable = new EmailNotifiable($reservation->email);
            $emailNotifiable->notify(new ReservationConfirmation($reservation));

            return view('application.callback.success');

        } catch (\Exception $e) {
            return $this->error();
        }
    }
}
