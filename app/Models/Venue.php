<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Venue extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'name', 'description', 'address', 'postal_code', 'city', 'email', 'phone', 'coc_number', 'tax_number', 'bank_number', 'avatar_id', 'cover_id', 'receipt_logo_id',
        'receipt_top', 'receipt_bottom',
    ];

    /**
     * Return all users that have access to this venue
     *
     * @return HasMany
     */
    public function user_venues(): HasMany
    {
        return $this->hasMany(UserVenue::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_venues');
    }

    public function units()
    {
        return $this->hasMany(Unit::class)->orderBy('name');
    }

    public function templates()
    {
        return $this->hasMany(Template::class);
    }

    public function weeks()
    {
        return $this->hasMany(Week::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
