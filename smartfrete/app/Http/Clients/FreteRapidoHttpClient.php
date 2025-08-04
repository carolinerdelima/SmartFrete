<?php

namespace App\Http\Clients;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\RequestException;

class FreteRapidoHttpClient
{
    protected string $baseUrl = 'https://sp.freterapido.com/api/v3';

    protected string $token;
    protected string $cnpj;
    protected string $platformCode;

    public function __construct()
    {
        $this->token = config('services.freterapido.token');
        $this->cnpj = config('services.freterapido.cnpj');
        $this->platformCode = config('services.freterapido.platform_code');
    }

    public function quote(array $volumes, string $zipcode): array
    {
        $payload = [
            'shipper' => [
                'registered_number' => $this->cnpj,
                'token' => $this->token,
                'platform_code' => $this->platformCode,
            ],
            'recipient' => [
                'type' => 0,
                'zipcode' => $zipcode,
                'country' => 'BRA',
            ],
            'dispatchers' => [
                [
                    'registered_number' => $this->cnpj,
                    'zipcode' => '29161376',
                    'volumes' => $volumes,
                ]
            ]
        ];

        try {
            $response = Http::post("{$this->baseUrl}/quote/simulate", $payload);

            if ($response->failed()) {
                throw new RequestException($response);
            }

            return $response->json();
        } catch (\Throwable $e) {
            throw new \RuntimeException("Erro ao consultar Frete Rápido: " . $e->getMessage(), 500);
        }
    }
}
