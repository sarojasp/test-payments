<?php

namespace Database\Factories;

use App\Models\Payment;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PaymentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Payment::class;


    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'uuid' => Str::uuid(),
            'payment_date' => $this->faker->dateTimeBetween('-2 days','now'),
            'expires_at' => $this->faker->dateTimeBetween('now','+31 days'),
            'status' => 'pending',
            'clp_usd' => rand('800', '950')
        ];
    }
}
