<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Recipe>
 */
class RecipeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => fake()->words(3, true),
            'instructions' => fake()->words(12, true),
            'description' => fake()->words(8, true),
            'time_estimate' => fake()->numberBetween(15, 120),
            'feature_image' => fake()->imageUrl()
        ];
    }
}
