<?php

namespace App\Models\Modules;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VenueModule extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = ['venue_id', 'module_id', 'settings'];
}
