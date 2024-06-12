<?php
namespace App\WebPayment;

enum PaymentStatus: string
{
    case Initialized = "initialized";
    case Open = "open";
    case Pending = "pending";
    case Authorized = "authorized";
    case Expired = "expired";
    case Failed = "failed";
    case Paid = "paid";
    case Canceled = "canceled";
    case Refunded = "refunded";
}
