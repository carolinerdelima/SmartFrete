<?php

namespace Tests\Feature;

use App\Models\Carrier;
use App\Models\Quote;
use App\Models\Volume;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Http\Clients\FreteRapidoHttpClient;
use Illuminate\Support\Facades\App;

class QuoteApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Cria o mock do client da Frete Rápido
        $mock = $this->createMock(FreteRapidoHttpClient::class);

        $mock->method('quote')->willReturn([
            'dispatchers' => [
                [
                    'offers' => [
                        [
                            'carrier' => [
                                'name' => 'FRETE TESTE',
                                'reference' => '123'
                            ],
                            'service' => 'EXPRESSO',
                            'delivery_time' => [
                                'days' => 3
                            ],
                            'final_price' => 99.99,
                            'cost_price' => 80.00,
                            'service_code' => 'EXP123'
                        ]
                    ]
                ]
            ]
        ]);

        App::instance(FreteRapidoHttpClient::class, $mock);
    }

    public function test_deve_retornar_422_quando_dados_estao_incompletos(): void
    {
        $response = $this->postJson('/api/quote', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'recipient.zipcode',
                'dispatchers',
                'simulation_type',
            ]);
    }

    public function test_deve_criar_ou_retornar_quote_existente_e_persistir_carriers_volumes(): void
    {
        $payload = [
            'recipient' => [
                'zipcode' => '90200000'
            ],
            'dispatchers' => [
                [
                    'volumes' => [
                        [
                            'category' => '1',
                            'amount' => 2,
                            'sku' => 'AB123',
                            'description' => 'Caixa de livros',
                            'height' => 0.2,
                            'width' => 0.3,
                            'length' => 0.4,
                            'unitary_price' => 45.50,
                            'unitary_weight' => 1.2
                        ]
                    ]
                ]
            ],
            'simulation_type' => [0]
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

        $this->assertEquals(1, Quote::count());
        $this->assertEquals(1, Volume::count());
        $this->assertEquals(1, Carrier::count());

        $quote = Quote::first();
        $volume = Volume::first();

        $this->assertEquals('AB123', $volume->sku);
        $this->assertEquals('90200000', $quote->recipient_zipcode);

        $this->assertDatabaseHas('carriers', [
            'quote_id' => $quote->id,
            'name' => 'FRETE TESTE',
            'service' => 'EXPRESSO'
        ]);

        $response2 = $this->postJson('/api/quote', $payload);
        $response2->assertStatus(200);

        $this->assertEquals(1, Quote::count());
        $this->assertEquals(1, Volume::count());
        $this->assertEquals(1, Carrier::count());
    }
}
