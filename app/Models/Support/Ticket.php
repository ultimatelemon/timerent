<?php

namespace App\Models\Support;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ticket extends Model
{
    use HasUuids, HasUuids;

    protected $fillable = ['status', 'closed_at'];

    public function ticket_messages()
    {
        return $this->hasMany(TicketMessage::class)->orderBy('created_at', 'desc');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}
