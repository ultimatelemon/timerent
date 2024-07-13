<?php

namespace App\WebPayment;

use App\Models\Venue;

interface PaymentProviderInterface
{
    public function __construct(string $key);
    public function startPayment(string $description, int $cents, string $return_url, string $webhook = null, string $email, Venue $venue) : Payment;
    public function getPayment($id) : Payment;
    public function refundPayment($id) : string|bool;


}