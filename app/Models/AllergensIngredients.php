<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AllergensIngredients extends Model
{
    use HasFactory;

    protected $fillable = [
        'allergen_id',
        'ingredient_id'
    ];
}
