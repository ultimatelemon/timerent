<?php

namespace App\WebPayment;

interface PaymentProviderInterface
{
    public function __construct(string $key);
    public function startPayment(string $description, int $cents, string $return_url, string $webhook = null, string $email, string $stripe_connect_id) : Payment;
    public function getPayment($id) : Payment;
    public function refundPayment($id) : string|bool;


}