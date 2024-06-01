<?php

namespace App\Models;

use App\Casts\JsonCast;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Testing\Fluent\Concerns\Has;

class Template extends Model
{
    use HasFactory, SoftDeletes, HasUuids;

    protected $casts = [
        'template' => JsonCast::class,
    ];

    protected $fillable = [
        'name',
        'template',
        'interval',
        'price',
        'visible'
    ];
}
