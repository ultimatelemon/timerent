<?php

namespace App\Helpers;

use App\Models\Product;
use App\Models\Reservation;
use App\WebPayment\PaymentStatus;
use Carbon\Carbon;

class ProductHelper {
    public static function checkIfProductMaxIsBookedToday(Product $product, string $date, Reservation $reservation = null): bool
    {
        if($product->max_per_day === 0) return false;

        $count = Reservation::where('date', $date)
            ->where('payment_status', PaymentStatus::Paid)
            ->orWhere('payment_status', PaymentStatus::Open)
            ->whereHas('products', function ($query) use ($product) {
                $query->where('products.id', $product->id);
            });

        if($reservation) $count = $count->whereNot('id', $reservation->id);

            $count->count();

        return $count >= $product->max_per_day;
    }
}