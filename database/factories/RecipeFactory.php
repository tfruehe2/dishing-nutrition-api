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
            'description' => fake()->words(8, true),
            'time_estimate' => fake()->numberBetween(15, 120),
            'servings' => fake()->numberBetween(2,8),
            'feature_image' => fake()->imageUrl(),
        ];
    }
}
