<?php

namespace App\Models;

use App\Utils\Permissions;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $fillable = ['name', 'bitfield'];

    public function venue()
    {
        return $this->belongsTo(Venue::class);
    }

    public function flags()
    {
        return (new Permissions($this->bitfield))->available();
    }
}
