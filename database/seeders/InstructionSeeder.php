<?php

namespace Database\Seeders;

use App\Models\Recipe;
use App\Models\RecipeInstruction;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InstructionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $recipes = Recipe::all();

        foreach($recipes as $index => $recipe)
        {
            RecipeInstruction::factory(5)
                ->withRecipe($recipe)
                ->create([
                    'order' => $index
                ]);
        }
    }
}
