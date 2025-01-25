<?php

namespace App\Http\Controllers\Stripe;

use App\Events\InvoiceGenerated;
use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Reservation;
use App\Models\Venue;
use App\Notifications\Application\ReservationConfirmation;
use App\Notifications\OnboardEmail;
use App\Notifications\Traits\EmailNotifiable;
use App\WebPayment\PaymentStatus;
use Carbon\Carbon;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Stripe\Exception\ApiErrorException;
use Stripe\StripeClient;

class StripeCallbackController extends Controller
{

    private StripeClient $client;

    public function __construct()
    {
        $this->client = new StripeClient(env('STRIPE_SECRET'));
    }

    /**
     * Handle a success payment for subscription
     * @throws ApiErrorException
     */
    public function success(Request $request): View|Factory|\Illuminate\Foundation\Application
    {
        $session_id = $request->session_id;

        $session = $this->client->checkout->sessions->retrieve($session_id, ['expand' => ['subscription']]);
        if($session->subscription?->id) {
            $period_end = Carbon::createFromTimestamp($session->subscription->current_period_end)->setHour(23)->setMinute(59)->setSecond(59)->toDateTimeString();

            $venue = Venue::where('stripe_subscription_id', $session->id)->firstOrFail();
            $venue->stripe_subscription_id = $session->subscription->id;
            $venue->stripe_current_period_ends_at = $period_end;
            $venue->save();

            $user = $venue->user_venues->where('owner', true)->first()->user;
            $venue->user_venues->where('owner', true)->first()->user->notify(new OnboardEmail($venue, $user));

            return view('application.callback.success');

        } else {
            ray()->charles();
            //TODO: FIx this in ApplicationCallbackController
            if($session->payment_status === 'paid') {
                $reservation = Reservation::where('payment_id', $session->id)->firstOrFail();
                $reservation->payment_status = PaymentStatus::Paid;
                $reservation->save();

                $invoice = Invoice::where('reservation_id', $reservation->id)->firstOrFail();
                $invoice->paid_at = Carbon::now();
                $invoice->save();

                event(new InvoiceGenerated($invoice));

                // TODO: Mail confirmation
                $emailNotifiable = new EmailNotifiable($reservation->email);
                $emailNotifiable->notify(new ReservationConfirmation($reservation));
//                Notification::route('email', $reservation->email)->notify(new ReservationConfirmation($reservation));

                return view('application.callback.success');
            }
        }

        return view('application.callback.success');
    }
}
