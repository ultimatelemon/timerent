<?php

namespace App\Helpers;

use App\Models\Venue;

class PaymentHelper
{
    public static function hasPaymentsEnabled(Venue $venue): bool
    {
        if($venue->payment_service_provider === 'timerent' && $venue->stripe_connect_id) return true;
        if($venue->payment_service_provider !== 'timerent' && $venue->payment_api_key) return true;
        return false;
    }
}