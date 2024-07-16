<?php

namespace App\Http\Controllers;

use App\Http\Resources\ReservationResource;
use App\Models\Reservation;
use App\Models\User;
use App\Models\Venue;
use App\Notifications\ReservationConfirmation;
use App\WebPayment\PaymentStatus;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class ReservationController extends ApiController
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
        $reservations = $venue->reservations();

        if($request->has('q'))
            $reservations = $reservations->where('id', 'ILIKE', "%{$request->q}%")
                ->orWhere('email', 'ILIKE', "%{$request->q}%");

        if($request->has('max'))
            $reservations->max($request->max);

        if($request->has('date') && $request->date === 'today')
            $reservations->where('date', Carbon::today());

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
        return $this->success($reservation->payment_status === PaymentStatus::Paid->value);
    }

    public function resendConfirmationMail(Venue $venue, Reservation $reservation): JsonResponse
    {
//        Notification::route('email', $reservation->email)->notify(new ReservationConfirmation($reservation));
        $user = User::where('email', 'info@timerent.nl')->firstOrFail();
        $user->notify(new ReservationConfirmation($reservation));
        Notification::route('email', ['info@timerent.nl' => 'Kevin Terpstra'])->notify(new ReservationConfirmation($reservation));
        return $this->success('Sent to ' . 'info@timerent.nl');
    }
}
