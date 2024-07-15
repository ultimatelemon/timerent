<?php

namespace App\Http\Controllers;

use App\Http\Resources\ReservationResource;
use App\Models\Reservation;
use App\Models\Venue;
use App\WebPayment\PaymentStatus;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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
}
