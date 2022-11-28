<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Recipe extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'name',
        'description',
        'instructions',
        'video_id',
        'feature_image'
    ];

    public function ingredients()
    {
        return $this->belongsToMany(Ingredient::class, 'ingredients_recipes')
            ->withPivot(['order', 'measurement_unit_id', 'quantity']);
    }

    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }
}
