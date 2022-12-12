<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\RecipeInstruction;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $users = \App\Models\User::factory(10)->create();
        foreach($users as $user)
        {
            \App\Models\BlogPost::factory(3)
                ->withAuthor($user)
                ->create();
        }

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $this->call([
            IngredientsSeeder::class,
            MeasureUnitsSeeder::class,
            RecipesSeeder::class,
            InstructionSeeder::class,
            RolePermissionSeeder::class,
        ]);

    }
}
