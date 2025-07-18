<?php

namespace Database\Factories;

use App\Models\Quote;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class QuoteFactory extends Factory
{
    protected $model = Quote::class;

    public function definition(): array
    {
        return [
            'recipient_zipcode'      => $this->faker->postcode(),
            'uuid'                   => (string) Str::uuid(),
            'status'                 => $this->faker->randomElement(['pending', 'success', 'failed']),
            'frete_rapido_request'   => [
                'recipient' => ['address' => ['zipcode' => $this->faker->postcode()]],
                'volumes' => [],
            ],
            'frete_rapido_response'  => [
                'carrier' => [
                    ['name' => 'Correios', 'service' => 'SEDEX', 'deadline' => 2, 'price' => 20.99]
                ]
            ],
            'response_time_ms'       => $this->faker->numberBetween(100, 800),
        ];
    }
}
