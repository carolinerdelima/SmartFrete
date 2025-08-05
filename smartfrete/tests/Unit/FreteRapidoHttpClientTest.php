<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

use App\Http\Clients\FreteRapidoHttpClient;

class FreteRapidoHttpClientTest extends TestCase
{
    protected array $volumes = [
        [
            'category'       => '1',
            'amount'         => 1,
            'unitary_weight' => 1.0,
            'unitary_price'  => 150.00,
            'sku'            => 'ABC123',
            'height'         => 0.1,
            'width'          => 0.1,
            'length'         => 0.1,
            'description'    => 'Produto teste',
        ]
    ];

    protected string $zipcode = '29161376';

    #[\PHPUnit\Framework\Attributes\Test]
    public function deve_retornar_dados_quando_resposta_da_api_for_sucesso(): void
    {
        $expectedResponse = [
            'carrier' => [
                [
                    'name'     => 'Transportadora Teste',
                    'service'  => 'Rápido',
                    'deadline' => 3,
                    'price'    => 129.90,
                ],
            ]
        ];

        Http::fake([
            config('services.freterapido.base_url') . '/quote/simulate' =>
                Http::response($expectedResponse, 200),
        ]);

        $client = new FreteRapidoHttpClient();
        $response = $client->quote($this->volumes, $this->zipcode);

        $this->assertIsArray($response);
        $this->assertArrayHasKey('carrier', $response);
        $this->assertEquals('Transportadora Teste', $response['carrier'][0]['name']);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function deve_lancar_excecao_quando_resposta_da_api_falhar(): void
    {
        Http::fake([
            config('services.freterapido.base_url') . '/quote/simulate' =>
                Http::response(['erro' => 'falhou'], 500),
        ]);

        $client = new FreteRapidoHttpClient();

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessageMatches('/Erro ao consultar Frete Rápido: HTTP request returned status code 500/');

        $client->quote($this->volumes, $this->zipcode);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function deve_lancar_excecao_quando_ocorrer_erro_de_conexao(): void
    {
        Http::fake([
            config('services.freterapido.base_url') . '/quote/simulate' => fn () => throw new \Exception('Falha na conexão'),
        ]);

        $client = new FreteRapidoHttpClient();

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessageMatches('/Erro ao consultar Frete Rápido: Falha na conexão/');

        $client->quote($this->volumes, $this->zipcode);
    }
}
