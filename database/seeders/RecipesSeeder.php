<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use function PHPSTORM_META\map;

class RecipesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $recipes = \App\Models\Recipe::factory(20)->create();

        foreach($recipes as $recipe)
        {
            $ingredient = fake()->randomElement(\App\Models\Ingredient::get()->pluck('id'));
            $recipe->ingredients()->sync([
                $ingredient => [
                    'order' => 1,
                    'measurement_unit_id' => 1,
                    'quantity' => 2.0
                ]
            ]);
        }

    }
}
