<?php

namespace Database\Seeders;

use App\Models\Ingredient;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class IngredientsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $ingredientsData = [
            [
                'name' => 'Flour'  
            ],
            [
                'name' => 'Rice'  
            ],
            [
                'name' => 'Olive Oil'  
            ],
            [
                'name' => 'Siracha'  
            ],
            [
                'name' => 'Apples'  
            ],
            [
                'name' => 'Water'  
            ],
            [
                'name' => 'Garlic'  
            ],
            [
                'name' => 'Onions'  
            ],
            [
                'name' => 'Potatoes'  
            ],
            [
                'name' => 'Soy Sauce'  
            ],
        ];

        foreach($ingredientsData as $data)
        {
            Ingredient::firstOrCreate($data);
        }
    }
}
