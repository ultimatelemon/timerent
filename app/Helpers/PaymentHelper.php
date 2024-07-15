<?php

namespace App\Helpers;

use App\Models\Setting;
use App\Models\Venue;

class PaymentHelper
{
    public static function hasPaymentsEnabled(Venue $venue): bool
    {
        $provider = Setting::where([['venue_id', '=', $venue->id], ['key', '=', 'payment_provider']])->first();
        $provider_key = Setting::where([['venue_id', '=', $venue->id], ['key', '=', 'payment_api_key']])->first();
        if($provider === 'timerent' && $venue->stripe_connect_id) return true;
        if($provider !== 'timerent' && $provider_key) return true;
        return false;
    }
}