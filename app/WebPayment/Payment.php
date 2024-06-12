<?php

namespace App\WebPayment;

class Payment
{
    private PaymentProviderInterface $paymentProvider;
    private ?string $payment_url; // Nullable if expired/cancelled/etc.
    private PaymentStatus $status;
    public string $description;
    public int $cents;
    public string $return_url;
    public string $webhook;
    public string $id;

    public function __construct(PaymentProviderInterface $paymentProvider, string $description, int $cents, string $return_url, string $webhook = null, string $id = null, PaymentStatus $paymentStatus = null, string $payment_url = null)
    {
        $this->id = $id;
        $this->description = $description;
        $this->cents = $cents;
        $this->return_url = $return_url;
        $this->webhook = $webhook;
        $this->status = $paymentStatus;
        $this->paymentProvider = $paymentProvider;
        $this->payment_url = $payment_url;
    }

    public function isPaid(): bool
    {
        return $this->status === PaymentStatus::Paid;
    }

    public function getStatus(): PaymentStatus
    {
        return $this->status;
    }

    public function refund($amount = null): bool
    {
        return $this->paymentProvider->refundPayment($this->id, $amount);
    }

    public function getPaymentUrl()
    {
        return $this->payment_url;
    }
}