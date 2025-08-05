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
        $request = [
            'recipient' => [
                'address' => [
                    'zipcode' => $this->faker->postcode()
                ]
            ],
            'volumes' => [
                [
                    'category'       => '1',
                    'amount'         => 1,
                    'unitary_weight' => 1.0,
                    'unitary_price'  => 150.00,
                    'sku'            => $this->faker->bothify('SKU-###'),
                    'height'         => 0.1,
                    'width'          => 0.1,
                    'length'         => 0.1
                ]
            ]
        ];

        return [
            'recipient_zipcode'      => $request['recipient']['address']['zipcode'],
            'uuid'                   => (string) Str::uuid(),
            'status'                 => $this->faker->randomElement(['pending', 'success', 'failed']),
            'frete_rapido_request'   => $request,
            'frete_rapido_response'  => [
                'dispatchers' => [
                    [
                        'offers' => [
                            [
                                'carrier' => ['name' => 'Correios', 'reference' => 123],
                                'service' => 'SEDEX',
                                'delivery_time' => ['days' => 2],
                                'final_price' => 20.99,
                                'cost_price' => 18.99,
                                'service_code' => 'SEDEX123'
                            ]
                        ]
                    ]
                ]
            ],
            'response_time_ms'       => $this->faker->numberBetween(100, 800),

            'payload_hash'           => hash('sha256', json_encode($request)),
        ];
    }
}
