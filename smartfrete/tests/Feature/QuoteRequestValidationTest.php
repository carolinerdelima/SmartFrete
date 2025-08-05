<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class QuoteRequestValidationTest extends TestCase
{
    use RefreshDatabase;

    #[\PHPUnit\Framework\Attributes\Test]
    public function deve_retornar_erro_quando_zipcode_nao_estiver_presente()
    {
        $payload = [
            'recipient' => ['address' => []],
            'simulation_type' => [0],
            'volumes' => [
                [
                    'category' => '1',
                    'amount' => 1,
                    'unitary_weight' => 1.0,
                    'unitary_price' => 150.00,
                    'sku' => 'ABC123',
                    'height' => 0.1,
                    'width' => 0.1,
                    'length' => 0.1
                ]
            ]
        ];

        $response = $this->postJson('/api/quote', $payload);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['recipient.address.zipcode']);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function deve_retornar_erro_quando_volumes_esta_vazio()
    {
        $payload = [
            'recipient' => ['address' => ['zipcode' => '29161376']],
            'simulation_type' => [0],
            'volumes' => []
        ];

        $response = $this->postJson('/api/quote', $payload);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['volumes']);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function deve_retornar_erro_quando_dados_do_volume_estao_incompletos()
    {
        $payload = [
            'recipient' => ['address' => ['zipcode' => '29161376']],
            'simulation_type' => [0],
            'volumes' => [
                ['category' => '1'] // faltando dados obrigatórios
            ]
        ];

        $response = $this->postJson('/api/quote', $payload);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors([
                    'volumes.0.amount',
                    'volumes.0.unitary_weight',
                    'volumes.0.unitary_price',
                    'volumes.0.sku',
                    'volumes.0.height',
                    'volumes.0.width',
                    'volumes.0.length',
                 ]);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function deve_passar_quando_dados_estao_validos()
    {
        $payload = [
            'recipient' => [
                'address' => ['zipcode' => '29161376']
            ],
            'simulation_type' => [0],
            'volumes' => [
                [
                    'category' => '1',
                    'amount' => 1,
                    'unitary_weight' => 1.0,
                    'unitary_price' => 150.00,
                    'sku' => 'ABC123',
                    'height' => 0.1,
                    'width' => 0.1,
                    'length' => 0.1
                ]
            ]
        ];

        $response = $this->postJson('/api/quote', $payload);

        $response->assertStatus(200);
    }
}
