<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Payment;
use Database\Factories\ClientFactory;
use Illuminate\Database\Seeder;

class TestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        for ($i=0; $i < 3 ; $i++) 
        { 
            $client = Client::factory()->create();

            for ($j=0; $j < rand(1,5) ; $j++) 
            { 
                $payment = Payment::factory()->create(['client_id' => $client->id]);
            }
        }
    }
}
