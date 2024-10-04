<?php

namespace App\Http\Controllers\Application;

use App\Helpers\PaymentHelper;
use App\Helpers\ProductHelper;
use App\Helpers\ReservationHelper;
use App\Http\Controllers\ApiController;
use App\Http\Requests\Venue\StoreReservation;
use App\Http\Resources\ReservationResource;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\Reservation;
use App\Models\ReservationTimeblock;
use App\Models\Setting;
use App\Models\Unit;
use App\Models\Venue;
use App\Notifications\Application\ApplicationReservationCancelled;
use App\WebPayment\Mollie\MolliePaymentClient;
use App\WebPayment\PaymentStatus;
use App\WebPayment\Timerent\TimerentPaymentClient;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;
use Mollie\Api\Exceptions\ApiException;
use Mollie\Api\Exceptions\IncompatiblePlatform;
use Mollie\Api\Exceptions\UnrecognizedClientException;
use Mollie\Api\MollieApiClient;
use function Laravel\Prompts\error;

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
        ray($request->all());
        $validatedRequest = $request->validated();
        $venue = Venue::where('subdomain', $validatedRequest['subdomain'])->firstOrFail();
        if(!PaymentHelper::hasPaymentsEnabled($venue)) return $this->error(['error' => 'Er is nog geen betaalprovider gekoppeld.']);
        $payment_provider = Setting::where([['key', '=', 'payment_provider'], ['venue_id', '=', $venue->id]])->firstOrFail()->value;

        $total = collect($validatedRequest['timeblocks'])->map(function ($x) {
            return $x['timeblock']['price'];
        })->sum();

        $taxLow = 0;
        $taxHigh = 0;

        $date = Carbon::now()->setDateFrom($validatedRequest['date'])->toDateString();

        $reservation = Reservation::create([
            'name' => $validatedRequest['name'],
            'phone_number' => $validatedRequest['phone_number'],
            'email' => strtolower($validatedRequest['email']),
            'comments' => $validatedRequest['comments'],
            'date' => $date,

            'venue_id' => $venue->id,

            'payment_provider' => $payment_provider,
            'payment_amount' => $total,
            'tax_high' => $taxHigh,
            'tax_low' => $taxLow,
            'payment_status' => PaymentStatus::Open,
        ]);

        $timeblockUnitIds = array_map(function($timeblock) {
            return $timeblock['unit_id'];
        }, $validatedRequest['timeblocks']);

        foreach($validatedRequest['products'] as $p) {
            $prod = Product::findOrFail($p);
            if(ProductHelper::checkIfProductMaxIsBookedToday($prod, $date, $reservation)) {
                $reservation->forceDelete();
                return $this->error(['error' => "Product '$prod->name' is maximaal gereserveerd voor vandaag"]);
            }

            if($prod->units) {
                foreach ($prod->units as $unit) {
                    if(!in_array($unit->id, $timeblockUnitIds)) {
                        $reservation->forceDelete();
                        return $this->error(['error' => "Product '$prod->name' is alleen te reserveren bij bijhorende unit(s)"]);
                    }
                }
            }

            $reservation->products()->save($prod);
            if($prod->price_per_timeblock) {
                $total += $prod->price * count($validatedRequest['timeblocks']);
            } else {
                $total += $prod->price;
            }

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

        $invoice = new Invoice;
        $invoice->name = $reservation->name;
        $invoice->email = $reservation->email;
        $invoice->phone_number = $reservation->phone_number;
        $invoice->payment_amount = $reservation->payment_amount;
        $invoice->tax_low = $reservation->tax_low;
        $invoice->tax_high = $reservation->tax_high;
        $invoice->payment_status = $reservation->payment_status;
        $invoice->sent_at = Carbon::now();
        $invoice->reservation_id = $reservation->id;
        $invoice->venue_id = $reservation->venue_id;
        $invoice->save();

        // Todo; PSP modules,
        $paymentUrl = '';
        switch($payment_provider) {
            case 'timerent':
                $client = new TimerentPaymentClient(env('STRIPE_SECRET'));
                $payment = $client->startPayment('Reservering via Timerent.nl', $total, 'http://'.$request->getHttpHost().'/callback/success?session_id={CHECKOUT_SESSION_ID}', 'http://'.$request->getHttpHost() .'/callback/success?session_id={CHECKOUT_SESSION_ID}', $validatedRequest['email'], $venue);
                $reservation->update(['payment_id' => $payment->id]);
                $paymentUrl = $payment->getPaymentUrl();
                break;

            case 'mollie':
                $client = new MolliePaymentClient(Crypt::decrypt($venue->payment_api_key));
                $payment = $client->startPayment('Reservering via Timerent.nl', $total, env('APP_URL') . '/confirmation/' . $reservation->id, env('MOLLIE_WEBHOOK'), $validatedRequest['email'], $venue);
                $reservation->update(['payment_id' => $payment->id]);
                $paymentUrl = $payment->getPaymentUrl();
        }

        return $this->success($paymentUrl);
    }

    public function index(Request $request)
    {
        $reservations = $request->member->reservations();

        if($request->has('q'))
            $reservations = $reservations->where('id', 'ILIKE', "%{$request->q}%");

        $reservations = $reservations->paginate(env('POSTS_PER_PAGE'));

        return $this->success(
            ReservationResource::collection($request->member->reservations),
            collect($reservations)->only(['from', 'to', 'total', 'per_page', 'last_page', 'current_page'])->toArray(),
        );
    }

    public function show(Reservation $reservation, Request $request): JsonResponse
    {
        $reservation = $request->member->reservations()->where('id', $reservation->id)->firstOrFail();
        return $this->success(new ReservationResource($reservation));
    }

    /**
     * @throws ApiException
     * @throws UnrecognizedClientException
     * @throws IncompatiblePlatform
     */
    public function cancel(Reservation $reservation, Request $request)
    {
        $reservation = $request->member->reservations()->where('id', $reservation->id)->firstOrFail();
        $hours = Setting::where([['key', '=', 'cancellation_hours'], ['venue_id', '=', $reservation->venue->id]])->firstOrFail()->value;
        $cancelAllowed = Carbon::parse($reservation->date)->setHour(intval(explode(':', explode(' ', $reservation->timeblocks->first()->from)[1])[0]))->setMinute(0)->setSecond(0) >= Carbon::now()->addHours(intval($hours));
        if(!$cancelAllowed) return $this->error(['message' => "Annulering is niet meer mogelijk, het is langer dan $hours uur voor de reservering."]);

        $psp = Setting::where([['key', '=', 'payment_provider'], ['venue_id', '=', $reservation->venue->id]])->firstOrFail()->value;
        if($psp !== $reservation->payment_provider) $this->error(['message' => "Annulering niet mogelijk, betalingsmethode is gewijzigd. Neem contact met ons op."]);

        switch($psp) {
            case 'mollie':
                $key = Setting::where([['key', '=', 'payment_api_key'], ['venue_id', '=', $reservation->venue->id]])->firstOrFail()->value;
                $client = new MolliePaymentClient(Crypt::decrypt($key));
                $refunded = $client->refundPayment($reservation->payment_id);
                $reservation->payment_status = PaymentStatus::Refunded;
                $reservation->canceled_at = Carbon::now();
                $reservation->save();
                $request->member->notify(new ApplicationReservationCancelled($reservation));
                return $this->success();

            case 'timerent':
                $client = new TimerentPaymentClient(env('STRIPE_SECRET'));
                $refunded = $client->refundPayment($reservation->payment_id);
                $reservation->payment_status = PaymentStatus::Refunded;
                $reservation->canceled_at = Carbon::now();
                $reservation->save();
                $request->member->notify(new ApplicationReservationCancelled($reservation));
                return $this->success();
        }

        return $this->error();
    }
}
