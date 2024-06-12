<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReservationTimeblock extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $fillable = [
        'from',
        'to',
        'date',
        'reservation_id',
        'venue_id',
        'canceled_at'
    ];

    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }
}
