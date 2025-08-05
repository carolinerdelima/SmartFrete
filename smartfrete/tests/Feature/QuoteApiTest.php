<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Quote;
use App\Models\Volume;
use App\Models\Carrier;

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

    public function test_deve_retornar_200_e_persistir_dados_quando_requisicao_valida(): void
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

        // Verifica se uma quote foi salva
        $this->assertEquals(1, Quote::count());

        // Verifica se o volume foi salvo e associado
        $this->assertEquals(1, Volume::count());
        $volume = Volume::first();
        $this->assertEquals('ABC123', $volume->sku);

        // Verifica se ao menos 1 carrier foi salvo para essa quote
        $this->assertGreaterThanOrEqual(1, Carrier::count());
        $this->assertDatabaseHas('carriers', [
            'quote_id' => Quote::first()->id
        ]);
    }
}
