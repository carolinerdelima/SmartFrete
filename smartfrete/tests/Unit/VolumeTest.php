<?php

namespace Tests\Unit;

use App\Models\Quote;
use App\Models\Volume;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class VolumeTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function cria_um_volume_com_sucesso()
    {
        $volume = Volume::factory()->create();

        $this->assertDatabaseHas('volumes', [
            'id' => $volume->id,
            'sku' => $volume->sku,
        ]);

        $this->assertInstanceOf(Volume::class, $volume);
    }

    #[Test]
    public function volume_esta_associado_a_uma_quote()
    {
        $volume = Volume::factory()->create();

        $this->assertNotNull($volume->quote);
        $this->assertInstanceOf(Quote::class, $volume->quote);
    }

    #[Test]
    public function volume_tem_dados_numericos_validos()
    {
        $volume = Volume::factory()->create();

        $this->assertIsNumeric($volume->unitary_weight);
        $this->assertIsNumeric($volume->price);
        $this->assertIsNumeric($volume->height);
        $this->assertIsNumeric($volume->width);
        $this->assertIsNumeric($volume->length);
        $this->assertIsNumeric($volume->amount);
    }

    #[Test]
    public function volume_pode_ser_criado_em_lote_para_uma_quote()
    {
        $quote = Quote::factory()->create();

        $volumes = Volume::factory()->count(3)->create([
            'quote_id' => $quote->id,
        ]);

        $this->assertCount(3, $quote->volumes);

        foreach ($volumes as $volume) {
            $this->assertEquals($quote->id, $volume->quote_id);
        }
    }

    #[Test]
    public function nao_cria_volume_sem_quote()
    {
        $this->expectException(\Illuminate\Database\QueryException::class);

        Volume::create([
            'category' => 1,
            'amount' => 1,
            'unitary_weight' => 1.0,
            'price' => 100.00,
            'sku' => 'SKU123',
            'height' => 10,
            'width' => 10,
            'length' => 10,
        ]);
    }

    #[Test]
    public function exclusao_de_quote_remove_volumes()
    {
        $quote = Quote::factory()->create();
        Volume::factory()->count(3)->create(['quote_id' => $quote->id]);

        $this->assertEquals(3, Volume::where('quote_id', $quote->id)->count());

        $quote->delete();

        $this->assertDatabaseMissing('volumes', ['quote_id' => $quote->id]);
    }

    #[Test]
    public function cria_volume_com_valores_altissimos()
    {
        $volume = Volume::factory()->create([
            'unitary_weight' => 99999999.99,
            'height' => 9999.999,
            'width' => 9999.999,
            'length' => 9999.999,
        ]);

        $this->assertEquals(99999999.99, $volume->unitary_weight);
    }

}
