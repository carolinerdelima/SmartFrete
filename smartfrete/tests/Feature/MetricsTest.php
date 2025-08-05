<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Quote;
use App\Models\Carrier;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MetricsTest extends TestCase
{
    use RefreshDatabase;

    public function test_deve_retornar_metricas_gerais_de_cotacoes()
    {
        $quote = Quote::factory()->create();

        Carrier::factory()->count(3)->create([
            'quote_id' => $quote->id,
            'final_price' => 100,
        ]);

        $response = $this->getJson('/api/metrics');

        $response->assertOk();
        $response->assertJsonStructure([
            'by_carrier' => [
                '*' => ['carrier', 'quotes_count', 'total_freight', 'average_freight']
            ],
            'cheapest_freight' => ['carrier', 'service', 'freight_price', 'delivery_days'],
            'most_expensive_freight' => ['carrier', 'service', 'freight_price', 'delivery_days'],
        ]);
    }

    public function test_deve_retornar_metricas_filtrando_pelas_ultimas_cotacoes()
    {
        $quotes = Quote::factory()->count(3)->sequence(
            ['created_at' => now()->subDays(3)],
            ['created_at' => now()->subDays(2)],
            ['created_at' => now()->subDay()]
        )->create();

        Carrier::factory()->create(['quote_id' => $quotes[0]->id, 'final_price' => 50]);
        Carrier::factory()->create(['quote_id' => $quotes[1]->id, 'final_price' => 150]);
        Carrier::factory()->create(['quote_id' => $quotes[2]->id, 'final_price' => 500]);

        $response = $this->getJson('/api/metrics?last_quotes=1');

        $response->assertOk();
        $this->assertEquals(500, $response->json('cheapest_freight.freight_price'));
        $this->assertEquals(500, $response->json('most_expensive_freight.freight_price'));
    }

    public function test_deve_retornar_erro_se_parametro_for_invalido()
    {
        $response = $this->getJson('/api/metrics?last_quotes=abc');

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('last_quotes');
    }
}
