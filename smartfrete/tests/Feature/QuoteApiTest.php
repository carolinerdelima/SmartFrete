<?php

namespace Tests\Feature;

use App\Models\Carrier;
use App\Models\Quote;
use App\Models\Volume;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Services\QuoteService;

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

    public function test_deve_criar_ou_retornar_quote_existente_e_persistir_carriers_volumes(): void
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
                    'unitary_price' => 150.00,
                    'sku' => 'ABC123',
                    'height' => 0.1,
                    'width' => 0.1,
                    'length' => 0.1
                ]
            ]
        ];

        $response1 = $this->postJson('/api/quote', $payload);
        $response1->assertStatus(200)
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

        // Verifica persistência
        $this->assertEquals(1, Quote::count());
        $this->assertEquals(1, Volume::count());
        $this->assertGreaterThanOrEqual(1, Carrier::count());

        $quote = Quote::first();
        $volume = Volume::first();

        $this->assertEquals('ABC123', $volume->sku);
        $this->assertEquals('29161376', $quote->recipient_zipcode);

        $this->assertDatabaseHas('carriers', [
            'quote_id' => $quote->id
        ]);

        // Segunda chamada com mesmo payload (deve reutilizar a quote)
        $response2 = $this->postJson('/api/quote', $payload);
        $response2->assertStatus(200);

        $this->assertEquals(1, Quote::count(), 'A quote não deve ser duplicada');
        $this->assertEquals(1, Volume::count(), 'O volume não deve ser duplicado');
        $this->assertGreaterThanOrEqual(1, Carrier::count(), 'Carriers devem continuar existentes');

        $expectedHash = QuoteService::calculatePayloadHash($payload);

        $this->assertDatabaseHas('quotes', [
            'payload_hash' => $expectedHash,
        ]);

    }
}
