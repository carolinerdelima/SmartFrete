<?php

namespace App\Http\Clients;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class FreteRapidoHttpClient
{
    protected string $cnpj;
    protected string $token;
    protected string $baseUrl;
    protected string $platformCode;

    public function __construct()
    {
        $this->baseUrl      = config('services.freterapido.base_url');
        $this->token         = config('services.freterapido.token');
        $this->cnpj          = config('services.freterapido.cnpj');
        $this->platformCode  = config('services.freterapido.platform_code');
    }

    /**
     * Realiza a simulação de cotação com a Frete Rápido.
     *
     * @param array $volumes     Volumes no formato da API da Frete Rápido
     * @param string $zipcode    CEP do destinatário
     * @param array $simulationType Tipos de simulação desejados. Ex: [0] (fracionada), [1] (lotação)
     * @return array
     * @throws \RuntimeException
     */
    public function quote(array $volumes, string $zipcode, array $simulationType = [0]): array
    {
        $payload = $this->buildPayload($volumes, $zipcode, $simulationType);

        try {
            $response = Http::timeout(10)              // tempo máximo de 10 segundos
                            ->retry(3, 200)            // 3 tentativas, com 200ms entre elas
                            ->post("{$this->baseUrl}/quote/simulate", $payload);

            if ($response->failed()) {
                // Loga erro com o body pra debugar
                Log::error('Erro na consulta Frete Rápido', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);

                throw new \RuntimeException(
                    "Erro ao consultar Frete Rápido: HTTP {$response->status()}:\n" . $response->body(),
                    $response->status()
                );
            }

            return $response->json();

        } catch (\Throwable $e) {
            throw new \RuntimeException("Erro ao consultar Frete Rápido: " . $e->getMessage(), 500);
        }
    }

    /**
     * Prepara o payload conforme a especificação da API Frete Rápido.
     */
    protected function buildPayload(array $volumes, string $zipcode, array $simulationType): array
    {
        return [
            'shipper' => [
                'registered_number' => $this->cnpj,
                'token'             => $this->token,
                'platform_code'     => $this->platformCode,
            ],
            'recipient' => [
                'type'     => 0,
                'zipcode'  => intval($zipcode),
                'country'  => 'BRA',
            ],
            'dispatchers' => [
                [
                    'registered_number' => $this->cnpj,
                    'zipcode'           => 29161376, // TODO: tornar dinâmico no futuro
                    'volumes'           => $volumes,
                ]
            ],
            'simulation_type' => $simulationType,
        ];
    }
}
