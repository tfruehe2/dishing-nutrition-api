<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BlogPost>
 */
class BlogPostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'title' => fake()->words(3, true),
            'description' => fake()->words(5, true),
            'feature_image' => fake()->imageUrl(),
            'contentHTML' => "",
            'author_id' => fake()->randomDigit()
        ];
    }

    public function withAuthor(User $user)
    {
        return $this->state(fn($attributes) => ['author_id' => $user->id]);
    }
}
