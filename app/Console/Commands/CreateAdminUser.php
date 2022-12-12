<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Database\QueryException;

class CreateAdminUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:admin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates admin user';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $firstName = $this->ask('What is your first name?');
        $lastName = $this->ask('What is your last name?');
        $email = $this->ask("What is your email?");

        $pw_confirmed = false;
        while(!$pw_confirmed)
        {
            $password = $this->secret('Please enter a password...');
            $confirm = $this->secret("Please confirm your password");

            if($password === $confirm && $password !== "")
            {
                $pw_confirmed = true;
                continue;
            }

            $this->info("Your passwords entries did not match. Please try again.");

        }

        try {
            $user = \App\Models\User::create([
                'name' => $firstName .' '.$lastName,
                'email' => $email, 
                'password' => bcrypt($password)
            ]);
            
            $user->assignRole('admin');

            $this->info("Successfully created admin user with id: {$user->id}");
        } catch(QueryException $e) {
            $this->info("Failed to create user.");
            $this->info($e->getMessage());
        }

        //POTENTIAL TODO: some validation for is user email already exists


    }
}