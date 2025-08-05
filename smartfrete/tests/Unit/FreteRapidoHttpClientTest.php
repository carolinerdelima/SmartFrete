<?php

namespace Tests\Unit;

use App\Http\Clients\FreteRapidoHttpClient;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class FreteRapidoHttpClientTest extends TestCase
{
    protected array $volumes = [
        [
            'category'       => '1',
            'amount'         => 1,
            'unitary_weight' => 1.0,
            'price'          => 150.00,
            'sku'            => 'ABC123',
            'height'         => 0.1,
            'width'          => 0.1,
            'length'         => 0.1,
        ]
    ];

    protected string $zipcode = '29161376';

    public function test_quote_retorna_dados_quando_api_responde_com_sucesso(): void
    {
        $respostaFake = [
            'carrier' => [
                [
                    'name'     => 'Transportadora A',
                    'service'  => 'Normal',
                    'deadline' => 5,
                    'price'    => 39.99,
                ],
            ]
        ];

        Http::fake([
            'https://sp.freterapido.com/api/v3/quote/simulate' => Http::response($respostaFake, 200),
        ]);

        $client = new FreteRapidoHttpClient();

        $resposta = $client->quote($this->volumes, $this->zipcode);

        $this->assertIsArray($resposta);
        $this->assertArrayHasKey('carrier', $resposta);
        $this->assertEquals('Transportadora A', $resposta['carrier'][0]['name']);
    }

    public function test_quote_lanca_excecao_quando_resposta_da_api_falha(): void
    {
        Http::fake([
            'https://sp.freterapido.com/api/v3/quote/simulate' => Http::response(['erro' => 'falhou'], 500),
        ]);

        Log::shouldReceive('error')->once();

        $client = new FreteRapidoHttpClient();

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessageMatches('/Erro ao consultar Frete Rápido/');

        $client->quote($this->volumes, $this->zipcode);
    }

    public function test_quote_lanca_excecao_em_erro_de_conexao(): void
    {
        Http::fake([
            'https://sp.freterapido.com/api/v3/quote/simulate' => fn () => throw new \Exception("Falha na conexão"),
        ]);

        $client = new FreteRapidoHttpClient();

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessageMatches('/Erro ao consultar Frete Rápido: Falha na conexão/');

        $client->quote($this->volumes, $this->zipcode);
    }
}
