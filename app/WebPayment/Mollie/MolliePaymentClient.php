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
        // TODO: Implement getPayment() method.
    }

    public function refundPayment($id): string|bool
    {
        // TODO: Implement refundPayment() method.
    }
}