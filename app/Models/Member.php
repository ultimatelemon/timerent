<?php

namespace App\Models;

use App\WebPayment\PaymentStatus;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Member extends Model implements MustVerifyEmail
{

    use HasFactory, HasUuids, Notifiable, HasApiTokens, SoftDeletes;

    protected $fillable = ['name', 'email', 'venue_id', 'loyality_points', 'pay_on_invoice', 'group_id'];

    public function reservations()
    {
        if (!$this->email_verified_at)
            return ray($this->email_verified_at);

        return $this->hasMany(Reservation::class, 'email', 'email')->where([
            ['payment_status', '!=', PaymentStatus::Expired],
            ['rebook_of', null],
        ])->orderBy('date', 'asc');
    }

    public function hasVerifiedEmail()
    {
        // TODO: Implement hasVerifiedEmail() method.
    }

    public function markEmailAsVerified()
    {
        // TODO: Implement markEmailAsVerified() method.
    }

    public function sendEmailVerificationNotification()
    {
        // TODO: Implement sendEmailVerificationNotification() method.
    }

    public function getEmailForVerification()
    {
        // TODO: Implement getEmailForVerification() method.
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }
}
