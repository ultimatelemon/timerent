<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Week extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $fillable = [
        'year',
        'week',
        'template_id',
        'venue_id',
        'unit_id'
    ];

    public function template()
    {
        return $this->belongsTo(Template::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }
}
