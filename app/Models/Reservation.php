<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reservation extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $fillable = [
        'number',
        'name',
        'phone_number',
        'comments',
        'email',
        'payment_provider',
        'payment_amount',
        'payment_id',
        'payment_status',
        'date',
        'unit_name',
        'unit_id',
        'payment_url',
        'rebook_id',
        'rebook_of',
        'canceled_at',
    ];

    public function venue()
    {
        return $this->belongsTo(Venue::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'email', 'email');
    }

    public function timeblocks()
    {
        return $this->hasMany(ReservationTimeblock::class);
    }

    public function logs()
    {
        //
    }

    public function products()
    {
        return $this->belongsToMany(Product::class);
    }
}
