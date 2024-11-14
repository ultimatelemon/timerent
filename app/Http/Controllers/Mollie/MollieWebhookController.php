<?php

namespace App\Http\Controllers\Mollie;

use App\Http\Controllers\ApiController;
use App\Models\Invoice;
use App\Models\Reservation;
use App\Notifications\Application\ReservationConfirmation;
use App\Notifications\Traits\EmailNotifiable;
use App\WebPayment\Mollie\MolliePaymentClient;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use function Sentry\captureMessage;

class MollieWebhookController extends ApiController
{

    public function __construct()
    {
        //
    }
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

    public function updatePayment(Request $request)
    {
        captureMessage('Mollie webhook started');
        if(!$request->has('id')) return null;

        try {

            $reservation = Reservation::where('payment_id', $request->get('id'))->firstOrFail();
            $venue = $reservation->venue;

            $mollie_key = $venue->payment_api_key;
            $client = new MolliePaymentClient(Crypt::decrypt($mollie_key));

            $payment = $client->getPayment($request->id);

            $reservation->payment_status = $payment->getStatus()->value;
            $reservation->save();

            $invoice = Invoice::where('reservation_id', $reservation->id)->firstOrFail();
            $invoice->paid_at = Carbon::now();
            $invoice->save();

            // TODO: Mail confirmation
            $emailNotifiable = new EmailNotifiable($reservation->email);
            $emailNotifiable->notify(new ReservationConfirmation($reservation));

        } catch (\Exception $e) {
            captureMessage($e->getMessage());
            return $this->error();
        }
        return $this->success();
    }
}
