<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MonthReport extends Model
{
    use HasUuids;

    protected $fillable = [
        'venue_id',
        'month',
        'year',
        'revenue_high',
        'revenue_low',
        'tax_amount_high',
        'tax_amount_low',
        'customer_count',
        'reservation_count',
        'period_from',
        'period_to',
        'available_at',
    ];

    public function venue(): BelongsTo
    {
        return $this->belongsTo(Venue::class);
    }
}
