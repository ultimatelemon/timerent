<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Invoice extends Model
{
    use HasUuids;

    protected $fillable = [
        'name',
        'email',
        'phone_number',
        'payment_amount',
        'revenue_high',
        'revenue_low',
        'tax_low',
        'tax_high',
        'payment_id',
        'payment_status',
        'payment_url',
        'sent_at',
        'sent_by',
        'paid_at',
        'member_id',
        'venue_id',
        'reservation_id',
    ];

    public function venue()
    {
        return $this->belongsTo(Venue::class);
    }

    public function reservation(): BelongsTo
    {
        return $this->belongsTo(Reservation::class);
    }
}
