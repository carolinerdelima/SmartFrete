<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class QuoteRequestValidationTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function deve_retornar_erro_quando_zipcode_nao_estiver_presente(): void
    {
        $payload = [
            'recipient' => [],
            'simulation_type' => [0],
            'dispatchers' => [
                [
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
                ]
            ]
        ];

        $response = $this->postJson('/api/quote', $payload);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['recipient.zipcode']);
    }

    #[Test]
    public function deve_retornar_erro_quando_volumes_esta_vazio(): void
    {
        $payload = [
            'recipient' => ['zipcode' => '29161376'],
            'simulation_type' => [0],
            'dispatchers' => [
                ['volumes' => []]
            ]
        ];

        $response = $this->postJson('/api/quote', $payload);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['dispatchers.0.volumes']);
    }

    #[Test]
    public function deve_retornar_erro_quando_dados_do_volume_estao_incompletos(): void
    {
        $payload = [
            'recipient' => ['zipcode' => '29161376'],
            'simulation_type' => [0],
            'dispatchers' => [
                [
                    'volumes' => [
                        ['category' => '1']
                    ]
                ]
            ]
        ];

        $response = $this->postJson('/api/quote', $payload);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors([
                        'dispatchers.0.volumes.0.amount',
                        'dispatchers.0.volumes.0.unitary_weight',
                        'dispatchers.0.volumes.0.unitary_price',
                        'dispatchers.0.volumes.0.sku',
                        'dispatchers.0.volumes.0.height',
                        'dispatchers.0.volumes.0.width',
                        'dispatchers.0.volumes.0.length',
                 ]);
    }

    #[Test]
    public function deve_passar_quando_dados_estao_validos(): void
    {
        $payload = [
            'recipient' => ['zipcode' => '29161376'],
            'simulation_type' => [0],
            'dispatchers' => [
                [
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
                ]
            ]
        ];

        $response = $this->postJson('/api/quote', $payload);

        $response->assertStatus(200);
    }
}
