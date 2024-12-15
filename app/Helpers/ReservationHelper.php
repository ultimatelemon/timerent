<?php

namespace App\Helpers;

use App\Models\ReservationTimeblock;
use App\WebPayment\PaymentStatus;
use Carbon\Carbon;

class ReservationHelper
{

    public static function checkIfTimeblockIsAvailable($unit_id, $from, $to, $reservation_id = null): bool
    {
        //TODO: Count on rows? E.g. boat rental can have 4 of one type boat. So it can be rented 4 times. (Need to check with subscriptions)

        if($from < Carbon::now()) return false;

        $timeblock = ReservationTimeblock::where([
            ['unit_id', $unit_id],
            ['to', '>', $from],
            ['canceled_at', '=', null]
        ]);


        if($reservation_id) $timeblock = $timeblock->where('reservation_id', '!=', $reservation_id);

        $timeblock = $timeblock->orderby('from', 'asc')->latest()->first();

        if(!$timeblock || $to < $timeblock->from) return true;

        if($timeblock->reservation->canceled_at) return true;

        $reservedStatus = [
            PaymentStatus::Paid->value,
            PaymentStatus::Open->value,
            PaymentStatus::Pending->value,
            PaymentStatus::Initialized->value,
            PaymentStatus::Authorized->value,
        ];

        if(!in_array($timeblock->reservation->payment_status, $reservedStatus)) return true;

        return false;
    }

//    public static function checkIfTimeblockIsAvailable($unit_id, $from, $to, $reservation_id = null): bool
//    {
//        //TODO: Count on rows? E.g. boat rental can have 4 of one type boat. So it can be rented 4 times. (Need to check with subscriptions)
//
//        $fromTime = Carbon::parse($from)->format('H:i');
//        $toTime = Carbon::parse($to)->format('H:i');
//
//        if(Carbon::parse($from)->isBefore(Carbon::now())) return false;
//
////        $timeblockQuery = ReservationTimeblock::where('unit_id', $unit_id)
////            ->where('canceled_at', '=', null)
////            ->where(function ($query) use ($fromTime, $toTime) {
////                $query->where('from', '<', $toTime) // Ensure there is no overlap with the requested time range
////                    ->where('to', '>', $fromTime); // Ensure there is no overlap with the requested time range
////            });
//
//        $timeblockQuery = ReservationTimeblock::where([
//            ['unit_id', $unit_id],
//            ['to', '>', $from],
//            ['canceled_at', '=', null]
//        ]);
//
//        // Exclude the current reservation if given (for editing purposes)
//        if($reservation_id) $timeblock = $timeblockQuery->where('reservation_id', '!=', $reservation_id);
//
//        // Get the latest overlapping reservation timeblock
//        $timeblock = $timeblockQuery->orderby('from', 'asc')->latest()->first();
//
//        // If no timeblock exists, the timeblock is available
//        if(!$timeblock) return true;
//
//        // If the reservation is canceled, the timeblock is available
//        if($timeblock->reservation->canceled_at) return true;
//
////        if(!$timeblock || $to < $timeblock->from) return true;
////
////        if($timeblock->reservation->canceled_at) return true;
//
//        $reservedStatus = [
//            PaymentStatus::Paid->value,
//            PaymentStatus::Open->value,
//            PaymentStatus::Pending->value,
//            PaymentStatus::Initialized->value,
//            PaymentStatus::Authorized->value,
//        ];
//
//        if(!in_array($timeblock->reservation->payment_status, $reservedStatus)) return true;
//
//        return false;
//    }
}