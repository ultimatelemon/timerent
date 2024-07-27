<?php

namespace App\WebPayment\Mollie;

use App\Models\Venue;
use App\WebPayment\Payment;
use App\WebPayment\PaymentProviderInterface;
use App\WebPayment\PaymentStatus;
use Mollie\Api\Exceptions\ApiException;
use Mollie\Api\Exceptions\IncompatiblePlatform;
use Mollie\Api\Exceptions\UnrecognizedClientException;
use Mollie\Api\MollieApiClient;
use Stripe\StripeClient;

class MolliePaymentClient implements PaymentProviderInterface
{

    private MollieApiClient $mollie;

    /**
     * @throws UnrecognizedClientException
     * @throws IncompatiblePlatform
     * @throws ApiException
     */
    public function __construct(string $key)
    {
        $this->mollie = new MollieApiClient();
        $this->mollie->setApiKey($key);
    }

    public function startPayment(string $description, int $cents, string $return_url, string $webhook = null, string $email, Venue $venue): Payment
    {
        $payment = $this->mollie->payments->create([
            'amount' => [
                'currency' => 'EUR',
                'value' => number_format($cents/100, 2, '.', ''),
            ],

            'description' => $description,
            'redirectUrl' => $return_url,
            'webhookUrl' => $webhook,
        ]);

        return new Payment($this, $description, $cents, $return_url, $webhook, $payment->id, PaymentStatus::Open, $payment->getCheckoutUrl());
    }

    public function getPayment($id): Payment
    {
        $payment = $this->mollie->payments->get($id);
        $amount = str_replace(".", "", $payment->amount->value) / 100; // Fix mollies pour development decisions
        return new Payment($this, $payment->description, $amount, $payment->redirectUrl, $payment->webhookUrl, $payment->id, $this->parsePaymentStatus($payment->status), $payment->getCheckoutUrl());
    }

    public function refundPayment($id): string|bool
    {
        $payment = $this->mollie->payments->get($id);
        $refund = $payment->refund([
           "amount" => [
               "currency" => "EUR",
               'value' => $payment->amount,
           ]
        ]);

        return $refund->id;
    }

    private function parsePaymentStatus(string $status) : \App\WebPayment\PaymentStatus
    {
        return match ($status) {
            \Mollie\Api\Types\PaymentStatus::STATUS_AUTHORIZED => \App\WebPayment\PaymentStatus::Authorized,
            \Mollie\Api\Types\PaymentStatus::STATUS_EXPIRED => \App\WebPayment\PaymentStatus::Expired,
            \Mollie\Api\Types\PaymentStatus::STATUS_OPEN => \App\WebPayment\PaymentStatus::Open,
            \Mollie\Api\Types\PaymentStatus::STATUS_PAID => \App\WebPayment\PaymentStatus::Paid,
            \Mollie\Api\Types\PaymentStatus::STATUS_PENDING => \App\WebPayment\PaymentStatus::Pending,
            default => \App\WebPayment\PaymentStatus::Failed,
        };
    }
}