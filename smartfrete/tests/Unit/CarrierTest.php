<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Carrier;
use App\Models\Quote;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;

class CarrierTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function cria_carrier_com_sucesso(): void
    {
        $quote = Quote::factory()->create();

        $carrier = Carrier::factory()->create([
            'quote_id'       => $quote->id,
            'name'           => 'Transportadora Rápida',
            'service'        => 'Entrega Expressa',
            'deadline_days'  => 2,
            'final_price'    => 35.90,
            'carrier_code'   => 'CRR-123',
            'service_code'   => 'SRV-456',
            'original_price' => 70.00,
        ]);

        $this->assertDatabaseHas('carriers', [
            'id'             => $carrier->id,
            'name'           => 'Transportadora Rápida',
            'service'        => 'Entrega Expressa',
            'deadline_days'  => 2,
            'final_price'    => 35.90,
            'carrier_code'   => 'CRR-123',
            'service_code'   => 'SRV-456',
            'original_price' => 70.00,
        ]);
    }

    #[Test]
    public function carrier_esta_associado_a_uma_quote(): void
    {
        $carrier = Carrier::factory()->create();

        $this->assertInstanceOf(Quote::class, $carrier->quote);
    }

    #[Test]
    public function carrier_tem_dados_numericos_validos(): void
    {
        $carrier = Carrier::factory()->create([
            'deadline_days'  => 3,
            'final_price'    => 42.75,
            'original_price' => 60.00,
        ]);

        $this->assertIsInt($carrier->deadline_days);
        $this->assertIsFloat($carrier->final_price + 0);
        $this->assertIsFloat($carrier->original_price + 0);
    }

    #[Test]
    public function pode_criar_varios_carriers_para_uma_quote(): void
    {
        $quote = Quote::factory()->create();

        $carriers = Carrier::factory()->count(3)->create([
            'quote_id' => $quote->id,
        ]);

        $this->assertCount(3, $carriers);
        $this->assertEquals($quote->id, $carriers->first()->quote_id);
    }
}
