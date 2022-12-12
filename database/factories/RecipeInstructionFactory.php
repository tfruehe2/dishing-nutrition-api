<?php

namespace Database\Factories;

use App\Models\Recipe;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\RecipeInstruction>
 */
class RecipeInstructionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'instruction' => fake()->words(4, true),
            'order' => fake()->randomDigit(),
        ];
    }

    public function withRecipe(Recipe $recipe) : RecipeInstructionFactory 
    {
        return $this->state(fn($attributes) => ['recipe_id' => $recipe->id]);
    }
}
