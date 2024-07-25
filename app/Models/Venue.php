<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Venue extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'name', 'description', 'address', 'postal_code', 'city', 'email', 'phone', 'coc_number', 'tax_number', 'bank_number', 'avatar_id', 'cover_id', 'receipt_logo_id',
        'receipt_top', 'receipt_bottom', 'subdomain', 'plan_id', 'stripe_customer_id', 'stripe_subscription_id', 'stripe_current_period_ends_at', 'stripe_connect_onboarded', 'stripe_connect_id',
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

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_venues');
    }

    public function units(): HasMany
    {
        return $this->hasMany(Unit::class)->orderBy('name');
    }

    public function templates(): HasMany
    {
        return $this->hasMany(Template::class);
    }

    public function weeks(): HasMany
    {
        return $this->hasMany(Week::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }

    public function settings(): HasMany
    {
        return $this->hasMany(Setting::class);
    }

    public function members(): HasMany
    {
        return $this->hasMany(Member::class);
    }
}
