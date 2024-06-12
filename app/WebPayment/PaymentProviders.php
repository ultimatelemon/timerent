<?php

namespace App\WebPayment;

enum PaymentProviders : string
{
    case TIMERENT = 'timerent';
    case MOLLIE = 'mollie';
    case STRIPE = 'stripe';
}