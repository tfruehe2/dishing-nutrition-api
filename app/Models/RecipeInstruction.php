<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecipeInstruction extends Model
{
    use HasFactory;

    protected $fillable = [
        'instruction',
        'recipe_id',
        'order'
    ];

    public function recipe()
    {
        return $this->belongsTo(Recipe::class);
    }
}
