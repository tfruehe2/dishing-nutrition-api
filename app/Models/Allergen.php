<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Allergen extends Model
{
    use HasFactory;

    protected $fillable = [
        'name'
    ];

    public function ingredients()
    {
        return $this->belongsToMany(Ingredient::class, 'allergens_ingredients');
    }


}
