<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MeasurementUnit extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'us_customary_unit'
    ];

    protected $casts = [
        'us_customary_unit' => 'boolean',
    ];
}
