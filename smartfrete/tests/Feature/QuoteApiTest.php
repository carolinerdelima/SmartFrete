<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class QuoteApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_deve_retornar_422_quando_dados_estao_incompletos(): void
    {
        $response = $this->postJson('/api/quote', []);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors([
                     'recipient.address.zipcode',
                     'volumes'
                 ]);
    }

    public function test_deve_retornar_200_e_retornar_carriers_quando_dados_estao_corretos(): void
    {
        $payload = [
            'recipient' => [
                'address' => [
                    'zipcode' => '29161376'
                ]
            ],
            'simulation_type' => [0],
            'volumes' => [
                [
                    'category' => '1',
                    'amount' => 1,
                    'unitary_weight' => 1.0,
                    'price' => 150.00,
                    'sku' => 'ABC123',
                    'height' => 0.1,
                    'width' => 0.1,
                    'length' => 0.1
                ]
            ]
        ];

        $response = $this->postJson('/api/quote', $payload);

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'carrier' => [
                         '*' => [
                             'name',
                             'service',
                             'deadline',
                             'price'
                         ]
                     ]
                 ]);

        // Verifica persistência no banco
        $this->assertDatabaseCount('quotes', 1);
        $this->assertDatabaseCount('volumes', 1);
        $this->assertDatabaseCount('carriers', 3); // se a API retornar 3 opções por exemplo
    }
}
