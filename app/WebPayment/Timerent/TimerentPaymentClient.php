<?php

namespace App\WebPayment\Timerent;

use App\Models\Venue;
use App\WebPayment\Payment;
use App\WebPayment\PaymentProviderInterface;
use App\WebPayment\PaymentStatus;
use Stripe\StripeClient;

class TimerentPaymentClient implements PaymentProviderInterface
{

    private StripeClient $stripe;

    public function __construct(string $key)
    {
        $this->stripe = new StripeClient($key);
    }

    public function startPayment(string $description, int $cents, string $return_url, string $webhook = null, string $email, Venue $venue): Payment
    {
        $paymentIntent = $this->stripe->checkout->sessions->create([
//            'payment_method_types' => ['card', 'ideal'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'eur',
                    'unit_amount' => $cents,
                    'product_data' => [
                        'name' => 'Reservering via Timerent.nl',
                        'description' => $description,
                    ],
                ],
                'quantity' => 1,
            ]],
            'payment_intent_data' => [
                'application_fee_amount' => env('APPLICATION_FEE_AMOUNT'),
                'transfer_data' => ['destination' => $venue->stripe_connect_id]
            ],
            'mode' => 'payment',
            'customer_email' => ($email),
            'success_url' => $return_url,
//            'cancel_url' => env('STRIPE_CANCEL_URL'),
        ]);

        return new Payment($this, $description, $cents, $return_url, $webhook, $paymentIntent->id, PaymentStatus::Open, $paymentIntent->url);
    }

    public function getPayment($id): Payment
    {
//        $payment = $this->mollie->payments->get($id);
//        $amount = str_replace(".", "", $payment->amount->value) / 100; // Fix mollie pour dev decisions
//        return new Payment($this, $payment->description, $amount, $payment->redirectUrl, $payment->webhookUrl, $payment->id, $this->parsePaymentStatus($payment->status), $payment->url);
    }

    public function refundPayment($id, $cents = null): bool|string
    {
        $payment = $this->stripe->checkout->sessions->retrieve($id);
        $intent = $this->stripe->paymentIntents->retrieve($payment->payment_intent);
        $refund = $this->stripe->refunds->create([
            'charge' => $intent->latest_charge
        ]);

        if($refund->status === 'succeeded') return $refund->id;

        return false;
    }
}