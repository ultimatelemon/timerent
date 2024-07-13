<?php

namespace App\Http\Controllers\Application;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\WebPayment\PaymentStatus;
use App\WebPayment\Timerent\MolliePaymentClient;
use Illuminate\Http\Request;
use Stripe\StripeClient;

class ApplicationCallbackController extends Controller
{
    public function success(Request $request)
    {
        ray('test2')->red();
        $session_id = $request->session_id;
        $client = new StripeClient(env('STRIPE_SECRET'));

        $session = $client->checkout->sessions->retrieve($session_id);
        if($session->payment_status === 'paid') {
            $reservation = Reservation::where('payment_id', $session->id)->firstOrFail();
            $reservation->payment_status = PaymentStatus::Paid;
            $reservation->save();

            // TODO: Mail confirmation

            return view('application.callback.success');
        }
    }
}
