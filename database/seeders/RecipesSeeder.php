<?php

namespace Database\Seeders;

use App\Models\Ingredient;
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
        $recipes = \App\Models\Recipe::factory(50)->create();

        foreach($recipes as $recipe)
        {
            $ingredients = Ingredient::all();
            $syncData = [];
            foreach($ingredients as $index => $ingredient)
            {
                $syncData[$ingredient->id] = [
                    'order' => $index,
                    'measurement_unit_id' => 1,
                    'quantity' => 2.0
                ];
            }
            
            $recipe->ingredients()->sync($syncData);
        }

    }
}
