<?php

namespace App\Http\Controllers;

use App\Http\Resources\ReservationResource;
use App\Models\Invoice;
use App\Models\Reservation;
use App\Models\Setting;
use App\Models\User;
use App\Models\Venue;
use App\Notifications\ReservationConfirmation;
use App\Notifications\Traits\EmailNotifiable;
use App\WebPayment\Mollie\MolliePaymentClient;
use App\WebPayment\PaymentStatus;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Notification;

class ReservationController extends ApiController implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('hasPermissions:VIEW_RESERVATIONS', only: ['index', 'show']),
            new Middleware('hasPermissions:MANAGE_RESERVATIONS', only: ['update']),
        ];
    }

    /**
     * Display a listing of the resource
     *
     * @param Request $request
     * @param Venue $venue
     * @return JsonResponse
     */
    public function index(Request $request, Venue $venue): JsonResponse
    {
        $reservations = $venue->reservations();

        if($request->has('q'))
            $reservations = $reservations->where('id', 'ILIKE', "%{$request->q}%")
                ->orWhere('email', 'ILIKE', "%{$request->q}%");

        if($request->has('max'))
            $reservations->max($request->max);

        if($request->has('date') && $request->date === 'today')
            $reservations->where('date', Carbon::today());

        $reservations = $reservations->orderBy('date', 'asc');

        $reservations = $reservations->paginate(env('POSTS_PER_PAGE'));

        return $this->success(
            ReservationResource::collection($reservations),
            collect($reservations)->only(['from', 'to', 'total', 'per_page', 'last_page', 'current_page'])->toArray(),
        );
    }

    /**
     * Show the specific resource
     *
     * @param Venue $venue
     * @param Reservation $reservation
     * @return JsonResponse
     */
    public function show(Venue $venue, Reservation $reservation): JsonResponse
    {
        return $this->success(new ReservationResource($reservation));
    }

    /**
     * Return if the reservation has been paid
     *
     * @param Reservation $reservation
     * @return JsonResponse
     */
    public function isPaid(Reservation $reservation): JsonResponse
    {

        switch($reservation->payment_provider) {
            case 'mollie':
                if($reservation->payment_status === PaymentStatus::Paid->value) break;
                $setting = Setting::where([['key', '=', 'payment_api_key'], ['venue_id', '=', $reservation->venue->id]])->firstOrFail()->value;
                $payment = new MolliePaymentClient(Crypt::decrypt($setting));
                $payment = $payment->getPayment($reservation->payment_id);
                if($payment->isPaid()) $reservation->update(['payment_status' => PaymentStatus::Paid->value]);

                $invoice = Invoice::where('reservation_id', $reservation->id)->firstOrFail();
                $invoice->paid_at = Carbon::now();
                $invoice->save();

                $emailNotifiable = new EmailNotifiable($reservation->email);
                $emailNotifiable->notify(new ReservationConfirmation($reservation));
        }

        return $this->success($reservation->payment_status === PaymentStatus::Paid->value);
    }

    public function resendConfirmationMail(Venue $venue, Reservation $reservation): JsonResponse
    {
        $emailNotifiable = new EmailNotifiable($reservation->email);
        $emailNotifiable->notify(new ReservationConfirmation($reservation));
        return $this->success('Sent');
    }

    public function update(Venue $venue, Reservation $reservation, Request $request): JsonResponse
    {
        $validatedRequest = $request->validate([
            'email' => 'required|string|email:rfc,dns',
            'comments' => 'nullable|sometimes|string|max:256',
            'phone_number' => 'required|numeric|digits:10',
        ]);

        $reservation->update($validatedRequest);
        return $this->success();
    }

}
