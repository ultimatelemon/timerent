<?php

namespace App\Http\Controllers\Application;

use App\Helpers\PaymentHelper;
use App\Helpers\ReservationHelper;
use App\Http\Controllers\ApiController;
use App\Http\Requests\Venue\StoreReservation;
use App\Models\Product;
use App\Models\Reservation;
use App\Models\ReservationTimeblock;
use App\Models\Setting;
use App\Models\Unit;
use App\Models\Venue;
use App\WebPayment\Mollie\MolliePaymentClient;
use App\WebPayment\PaymentStatus;
use App\WebPayment\Timerent\TimerentPaymentClient;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
use Mollie\Api\Exceptions\IncompatiblePlatform;
use Mollie\Api\Exceptions\UnrecognizedClientException;
use Mollie\Api\MollieApiClient;

class ApplicationReservationController extends ApiController
{

    /**
     * Store a new reservation
     *
     * @param StoreReservation $request
     * @return JsonResponse
     */
    public function store(StoreReservation $request): JsonResponse
    {
        $validatedRequest = $request->validated();
        $venue = Venue::where('subdomain', $validatedRequest['subdomain'])->firstOrFail();
        if(!PaymentHelper::hasPaymentsEnabled($venue)) return $this->error(['error' => 'Er is nog geen betaalprovider gekoppeld.']);

        $total = collect($validatedRequest['timeblocks'])->map(function ($x) {
            return $x['timeblock']['price'];
        })->sum();

        $taxLow = 0;
        $taxHigh = 0;

        $date = Carbon::now()->setDateFrom($validatedRequest['date']);

        $reservation = Reservation::create([
            'name' => $validatedRequest['name'],
            'phone_number' => $validatedRequest['phone_number'],
            'email' => $validatedRequest['email'],
            'comments' => $validatedRequest['comments'],
            'date' => $validatedRequest['date'],

            'venue_id' => $venue->id,

            'payment_provider' => $validatedRequest['payment_provider'] ?? 'timerent',
            'payment_amount' => $total,
            'tax_high' => $taxHigh,
            'tax_low' => $taxLow,
            'payment_status' => PaymentStatus::Open,
        ]);

        foreach($validatedRequest['products'] as $p) {
            $prod = Product::findOrFail($p);
            $reservation->products()->save($prod);
            $total += $prod->price;

            if($prod->tax_percentage === 21) {
                $taxHigh += round(($prod->price - ($prod->price / 1.21)));
                $reservation->update(['tax_high' => $taxHigh]);
            }
            if($prod->tax_percentage === 9) {
                $taxLow += round(($prod->price - ($prod->price / 1.09)));
                $reservation->update(['tax_low' => $taxLow]);
            }

            $reservation->update(['payment_amount' => $total]);
        }

        $timeblocks = [];
        foreach ($validatedRequest['timeblocks'] as $timeblock) {

            $from = Carbon::create($validatedRequest['date'])->setHour(intval(explode(':', $timeblock['timeblock']['from'])[0]))->setMinute(intval(explode(':', $timeblock['timeblock']['from'])[1]));
            $to = Carbon::create($validatedRequest['date'])->setHour(intval(explode(':', $timeblock['timeblock']['to'])[0]))->setMinute(intval(explode(':', $timeblock['timeblock']['to'])[1]));

            if (!ReservationHelper::checkIfTimeblockIsAvailable($timeblock['timeblock']['unit_id'], $from, $to)) return $this->error(['error' => '1 of meerdere tijdblokken zijn niet beschikbaar.']);

            $unit = Unit::where([
                ['id', $timeblock['timeblock']['unit_id']],
                ['venue_id', $venue->id],
            ])->firstOrFail();

            if($unit->tax_percentage === 21) {
                $taxHigh += round(($timeblock['timeblock']['price'] - ($timeblock['timeblock']['price'] / 1.21)));
                $reservation->update(['tax_high' => $taxHigh]);
            }
            if($unit->tax_percentage === 9) {
                $taxLow += round(($timeblock['timeblock']['price'] - ($timeblock['timeblock']['price'] / 1.09)));
                $reservation->update(['tax_low' => $taxLow]);
            }

            $timeblocks[] = [
                'id' => Str::uuid(),
                'reservation_id' => $reservation->id,
                'venue_id' => $venue->id,
                'unit_id' => $unit->id,
                'unit_name' => $unit->name,
                'from' => Carbon::parse($date)->setHour(intval(explode(':', $timeblock['timeblock']['from'])[0]))->setMinute(intval(explode(':', $timeblock['timeblock']['from'])[1]))->setSecond(0),
                'to' => Carbon::parse($date)->setHour(intval(explode(':', $timeblock['timeblock']['from'])[0]))->setMinute(intval(explode(':', $timeblock['timeblock']['from'])[1]))->setSecond(0)->addMinutes($timeblock['timeblock']['interval'] - 1),
                'interval' => $timeblock['timeblock']['interval'],
                'price' => $timeblock['timeblock']['price'],
            ];
        }

        ReservationTimeblock::insert($timeblocks);

        // Todo; PSP modules,
        $paymentUrl = '';
        $payment_provider = Setting::where([['key', '=', 'payment_provider'], ['venue_id', '=', $venue->id]])->firstOrFail()->value;
        switch($payment_provider) {
            case 'timerent':
                $client = new TimerentPaymentClient(env('STRIPE_SECRET'));
                $payment = $client->startPayment('Reservering via Timerent.nl', $total, 'http://'.$request->getHttpHost().'/callback/success?session_id={CHECKOUT_SESSION_ID}', 'http://'.$request->getHttpHost() .'/callback/success?session_id={CHECKOUT_SESSION_ID}', $validatedRequest['email'], $venue);
                $reservation->update(['payment_id' => $payment->id]);
                $paymentUrl = $payment->getPaymentUrl();
                break;

            case 'mollie':
                $api_key = Setting::where([['key', '=', 'payment_api_key'], ['venue_id', '=', $venue->id]])->firstOrFail()->value;
                $client = new MolliePaymentClient($api_key);
                $payment = $client->startPayment('Reservering via Timerent.nl', $total, env('MOLLIE_WEBHOOK'), env('MOLLIE_WEBHOOK'), $validatedRequest['email'], $venue);
                $reservation->update(['payment_id' => $payment->id]);
                $paymentUrl = $payment->getPaymentUrl();
        }

        return $this->success($paymentUrl);
    }
}
