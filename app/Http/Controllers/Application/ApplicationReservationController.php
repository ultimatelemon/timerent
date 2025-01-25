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
        $validated = $request->validated();
        $venue = Venue::where('subdomain', $validated['subdomain'])->firstOrFail();
        if(!PaymentHelper::hasPaymentsEnabled($venue)) return $this->error(['error' => 'Er is nog geen betaalprovider gekoppeld of API token is ongeldig.']);
        $payment_provider = $venue->payment_service_provider;

        $total = collect($validated['timeblocks'])->sum(fn($x) => $x['timeblock']['price']);

        $taxLow = $taxHigh = $revenueHigh = $revenueLow = 0;

        $date = Carbon::now()->setDateFrom($validated['date'])->toDateString();

        $reservation = Reservation::create([
            'name' => $validated['name'],
            'phone_number' => $validated['phone_number'],
            'email' => strtolower($validated['email']),
            'comments' => $validated['comments'],
            'date' => $date,
            'venue_id' => $venue->id,
            'payment_provider' => $payment_provider,
            'payment_amount' => $total,
            'tax_high' => $taxHigh,
            'tax_low' => $taxLow,
            'payment_status' => PaymentStatus::Open,
        ]);

        $timeblockUnitIds = array_column($validated['timeblocks'], 'unit_id');

        foreach($validated['products'] as $prod) {
            $product = Product::findOrFail($prod['id']);
            $productCount = intval($prod['count']);

            if(ProductHelper::checkIfProductMaxIsBookedToday($product, $date, $reservation)) {
                $reservation->forceDelete();
                return $this->error(['error' => "Product '$prod->name' is maximaal gereserveerd voor vandaag"]);
            }

            if($product->units->pluck('id')->diff($timeblockUnitIds)->isNotEmpty()) {
                $reservation->forceDelete();
                return $this->error(['error' => "Product '$product->name' is alleen te reserveren bij bijhorende unit(s)"]);
            }

            $timeblockMultiplier = ($product->price_per_timeblock ? count($validated['timeblocks']) : 1) * $productCount;
            ray($productCount)->red();
            ray($timeblockMultiplier)->orange();
            $total += floor(($product->price *  $timeblockMultiplier));
            $taxLow += floor($product->tax_percentage === 9 ? round(($product->price * $timeblockMultiplier) - (($product->price * $timeblockMultiplier) / 1.09), 2) : 0);
            $taxHigh += floor($product->tax_percentage === 21 ? round(($product->price * $timeblockMultiplier) - (($product->price * $timeblockMultiplier) / 1.21), 2) : 0);
            $revenueHigh += floor($product->tax_percentage === 21 ? $product->price * $timeblockMultiplier : 0);
            $revenueLow += floor($product->tax_percentage === 9 ? $product->price * $timeblockMultiplier : 0);

            $reservation->update([
                'payment_amount' => $total,
                'revenue_high' => $revenueHigh,
                'revenue_low' => $revenueLow,
                'tax_high' => $taxHigh,
                'tax_low' => $taxLow,
            ]);

            $reservation->products()->save($product, ['count' => $productCount]);
        }
//
//        foreach($validated['products'] as $productId) {
//            $prod = Product::findOrFail($productId);
//
//            if(ProductHelper::checkIfProductMaxIsBookedToday($prod, $date, $reservation)) {
//                $reservation->forceDelete();
//                return $this->error(['error' => "Product '$prod->name' is maximaal gereserveerd voor vandaag"]);
//            }
//
//            if($prod->units->pluck('id')->diff($timeblockUnitIds)->isNotEmpty()) {
//                $reservation->forceDelete();
//                return $this->error(['error' => "Product '$prod->name' is alleen te reserveren bij bijhorende unit(s)"]);
//            }
//
//            $timeblockMultiplier = $prod->price_per_timeblock ? count($validated['timeblocks']) : 1;
//            $total += floor(($prod->price * ($prod->price_per_timeblock ? $timeblockMultiplier : 1)));
//            $taxLow += floor($prod->tax_percentage === 9 ? round(($prod->price * $timeblockMultiplier) - (($prod->price * $timeblockMultiplier) / 1.09), 2) : 0);
//            $taxHigh += floor($prod->tax_percentage === 21 ? round(($prod->price * $timeblockMultiplier) - (($prod->price * $timeblockMultiplier) / 1.21), 2) : 0);
//            $revenueHigh += floor($prod->tax_percentage === 21 ? $prod->price * $timeblockMultiplier : 0);
//            $revenueLow += floor($prod->tax_percentage === 9 ? $prod->price * $timeblockMultiplier : 0);
//
//            $reservation->update([
//                'payment_amount' => $total,
//                'revenue_high' => $revenueHigh,
//                'revenue_low' => $revenueLow,
//                'tax_high' => $taxHigh,
//                'tax_low' => $taxLow,
//            ]);
//
//            $reservation->products()->save($prod);
//        }

        $timeblocks = [];
        foreach ($validated['timeblocks'] as $timeblock) {

            $from = Carbon::create($validated['date'])->setHour(intval(explode(':', $timeblock['timeblock']['from'])[0]))->setMinute(intval(explode(':', $timeblock['timeblock']['from'])[1]));
            $to = Carbon::create($validated['date'])->setHour(intval(explode(':', $timeblock['timeblock']['to'])[0]))->setMinute(intval(explode(':', $timeblock['timeblock']['to'])[1]));

            if (!ReservationHelper::checkIfTimeblockIsAvailable($timeblock['timeblock']['unit_id'], $from, $to)) return $this->error(['error' => '1 of meerdere tijdblokken zijn niet beschikbaar.']);

            $unit = Unit::where('id', $timeblock['timeblock']['unit_id'])->where('venue_id', $venue->id)->firstOrFail();
            $taxHigh += floor($unit->tax_percentage === 21 ? round($timeblock['timeblock']['price'] - ($timeblock['timeblock']['price'] / 1.21)) : 0);
            $taxLow += floor($unit->tax_percentage === 9 ? round($timeblock['timeblock']['price'] - ($timeblock['timeblock']['price'] / 1.09)) : 0);
            ray($timeblock['timeblock']['price'])->red();
            $revenueHigh += floor($unit->tax_percentage === 21 ? $timeblock['timeblock']['price'] : 0);
            $revenueLow += floor($unit->tax_percentage === 9 ? $timeblock['timeblock']['price'] : 0);

            $reservation->update([
                'revenue_high' => $revenueHigh,
                'revenue_low' => $revenueLow,
                'tax_low' => $taxLow,
                'tax_high' => $taxHigh,
            ]);

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

        $invoice = Invoice::create([
            'name' => $reservation->name,
            'email' => $reservation->email,
            'phone_number' => $reservation->phone_number,
            'payment_amount' => $reservation->payment_amount,
            'revenue_high' => $reservation->revenue_high,
            'revenue_low' => $reservation->revenue_low,
            'tax_high' => $reservation->tax_high,
            'tax_low' => $reservation->tax_low,
            'payment_status' => $reservation->payment_status,
            'sent_at' => now(),
            'reservation_id' => $reservation->id,
            'venue_id' => $venue->id,
        ]);

        // Todo; PSP modules,
        $paymentUrl = '';
        switch($payment_provider) {
            case 'timerent':
                $client = new TimerentPaymentClient(env('STRIPE_SECRET'));
                $payment = $client->startPayment('Reservering via Timerent.nl', $total, 'http://'.$request->getHttpHost().'/callback/success?session_id={CHECKOUT_SESSION_ID}', 'http://'.$request->getHttpHost() .'/callback/success?session_id={CHECKOUT_SESSION_ID}', $validated['email'], $venue);
                $reservation->update(['payment_id' => $payment->id]);
                $paymentUrl = $payment->getPaymentUrl();
                break;

            case 'mollie':
                ray($venue->payment_api_key)->red();
                ray(Crypt::decrypt($venue->payment_api_key))->green();
                $client = new MolliePaymentClient(Crypt::decrypt($venue->payment_api_key));
                $payment = $client->startPayment('Reservering via Timerent.nl', $total, env('APP_URL') . '/confirmation/' . $reservation->id, env('MOLLIE_WEBHOOK'), $validated['email'], $venue);
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
        $hours = $reservation->venue->cancellation_hours;
        $cancelAllowed = Carbon::parse($reservation->date)->setHour(intval(explode(':', explode(' ', $reservation->timeblocks->first()->from)[1])[0]))->setMinute(0)->setSecond(0) >= Carbon::now()->addHours(intval($hours));
        if(!$cancelAllowed) return $this->error(['message' => "Annulering is niet meer mogelijk, het is langer dan $hours uur voor de reservering."]);

        $psp = $reservation->venue->payment_service_provider;
        if($psp !== $reservation->payment_provider) $this->error(['message' => "Annulering niet mogelijk, betalingsmethode is gewijzigd. Neem contact met ons op."]);

        switch($psp) {
            case 'mollie':
                $key = $reservation->venue->payment_api_key;
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
