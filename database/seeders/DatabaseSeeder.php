<?php

namespace Database\Seeders;

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
        // \App\Models\User::factory(10)->create();

        // Load production seeder
        if (config('app.env') === 'production') 
        {
            // Call production Seeder
        } else {
            $this->call(TestSeeder::class);
        }
    }
}


