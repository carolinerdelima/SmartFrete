<?php

namespace App\Services;

use Illuminate\Support\Str;

use App\Models\Quote;
use App\Repositories\QuoteRepository;
use App\Repositories\CarrierRepository;
use App\Http\Clients\FreteRapidoHttpClient;

/**
 * Service responsável por criar ou recuperar cotações
 */
class QuoteService
{
    public function __construct(
        protected FreteRapidoHttpClient $client,
        protected QuoteRepository $quoteRepository,
        protected CarrierRepository $carrierRepository,
    ) {}

    /**
     * Cria ou retorna uma cotação existente baseada nos dados enviados.
     *
     * @param array $data Dados do payload enviado pelo cliente.
     * @return Quote Cotação criada ou encontrada com os dados persistidos.
     *
     * @throws \RuntimeException Em caso de erro na requisição à Frete Rápido.
     */
    public function createQuote(array $data): Quote
    {
        $payloadHash = self::calculatePayloadHash($data);

        // Verifica se já existe cotação com mesmo payload
        $quote = Quote::where('payload_hash', $payloadHash)->with('carriers')->first();
        if ($quote) {
            return $quote;
        }

        $uuid = Str::uuid()->toString();

        // Extrai volumes e cep do primeiro dispatcher
        $firstDispatcher   = $data['dispatchers'][0] ?? null;
        $volumes           = $firstDispatcher['volumes'] ?? [];
        $originZipcode     = $firstDispatcher['zipcode'] ?? null;
        $recipientZipcode  = $data['recipient']['zipcode'];

        $freteRapidoResponse = $this->client->quote(
            $volumes,
            $recipientZipcode,
            $data['simulation_type']
        );

        $quoteData = [
            'uuid'                  => $uuid,
            'payload_hash'          => $payloadHash,
            'recipient_zipcode'     => $recipientZipcode,
            'frete_rapido_request'  => $data,
            'frete_rapido_response' => $freteRapidoResponse,
            'response_time_ms'      => 0,
        ];

        $volumesData = collect($volumes)->map(function ($volume) {
            return array_merge($volume, [
                'price' => $volume['unitary_price'] ?? 0,
            ]);
        })->toArray();

        // Cria cotação e salva volumes
        $quote = $this->quoteRepository->store($quoteData, $volumesData);

        // Processa e insere os carriers únicos
        $carriersData = collect(data_get($freteRapidoResponse, 'dispatchers.0.offers', []))
            ->map(function ($item) use ($quote) {
                return [
                    'quote_id'       => $quote->id,
                    'name'           => data_get($item, 'carrier.name'),
                    'service'        => data_get($item, 'service'),
                    'deadline_days'  => data_get($item, 'delivery_time.days'),
                    'final_price'    => data_get($item, 'final_price'),
                    'original_price' => data_get($item, 'cost_price'),
                    'carrier_code'   => data_get($item, 'carrier.reference'),
                    'service_code'   => data_get($item, 'service_code'),
                ];
            })
            ->unique(fn ($item) =>
                $item['quote_id'] . '|' . $item['carrier_code'] . '|' . $item['service_code']
            )
            ->values()
            ->toArray();

        $this->carrierRepository->upsertCarriers($carriersData);

        return $quote->load('carriers');
    }

    /**
     * Calcula o hash do payload, ordenando os dados recursivamente.
     *
     * @param array $data Payload original
     * @return string Hash SHA-256 do payload ordenado
     */
    public static function calculatePayloadHash(array $data): string
    {
        $sortRecursively = function (&$array) use (&$sortRecursively) {
            if (!is_array($array)) return;
            foreach ($array as &$value) {
                if (is_array($value)) {
                    $sortRecursively($value);
                }
            }
            ksort($array);
        };

        $sortRecursively($data);

        return hash('sha256', json_encode($data));
    }
}
