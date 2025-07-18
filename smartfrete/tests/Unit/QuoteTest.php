<?php

namespace Tests\Unit;

use App\Models\Quote;
use App\Models\Volume;
use App\Models\Carrier;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class QuoteTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function cria_quote_com_sucesso(): void
    {
        $quote = Quote::factory()->create([
            'recipient_zipcode' => '91520-540',
        ]);

        $this->assertDatabaseHas('quotes', [
            'id' => $quote->id,
            'recipient_zipcode' => '91520-540',
        ]);
    }

    #[Test]
    public function quote_pode_ter_varios_volumes(): void
    {
        $quote = Quote::factory()->create();
        $volumes = Volume::factory()->count(3)->create(['quote_id' => $quote->id]);

        $this->assertCount(3, $quote->volumes);
        $this->assertInstanceOf(Volume::class, $quote->volumes->first());
    }

    #[Test]
    public function quote_pode_ter_varios_carriers(): void
    {
        $quote = Quote::factory()->create();
        $carriers = Carrier::factory()->count(2)->create(['quote_id' => $quote->id]);

        $this->assertCount(2, $quote->carriers);
        $this->assertInstanceOf(Carrier::class, $quote->carriers->first());
    }

    #[Test]
    public function quote_retornada_deve_ter_relacionamentos_carregados(): void
    {
        $quote = Quote::factory()
            ->has(Volume::factory()->count(2))
            ->has(Carrier::factory()->count(2))
            ->create();

        $quote = Quote::with('volumes', 'carriers')->find($quote->id);

        $this->assertTrue($quote->relationLoaded('volumes'));
        $this->assertTrue($quote->relationLoaded('carriers'));
    }

    #[Test]
    public function quote_com_volumes_e_carriers_persistidos_juntos(): void
    {
        $quote = Quote::factory()->create();

        $volume = Volume::factory()->create(['quote_id' => $quote->id]);
        $carrier = Carrier::factory()->create(['quote_id' => $quote->id]);

        $this->assertTrue($quote->volumes->contains($volume));
        $this->assertTrue($quote->carriers->contains($carrier));
    }

    #[Test]
    public function quote_pode_ser_criada_em_lote(): void
    {
        $quotes = Quote::factory()->count(5)->create();
        $this->assertCount(5, Quote::all());
    }

    #[Test]
    public function quote_tem_zipcode_valido(): void
    {
        $quote = Quote::factory()->create(['recipient_zipcode' => '12345-678']);
        $this->assertMatchesRegularExpression('/^\d{5}-\d{3}$/', $quote->recipient_zipcode);
    }
}
