<?php

namespace App\Services;

use App\Http\Clients\FreteRapidoHttpClient;
use App\Models\Quote;
use App\Repositories\QuoteRepository;
use App\Repositories\CarrierRepository;
use Illuminate\Support\Str;

class QuoteService
{
    public function __construct(
        protected FreteRapidoHttpClient $client,
        protected QuoteRepository $quoteRepository,
        protected CarrierRepository $carrierRepository,
    ) {}

    public function createQuote(array $data): Quote
    {
        $payloadHash = self::calculatePayloadHash($data);

        // Se já existe a cotação, retorna com os carriers carregados
        $quote = Quote::where('payload_hash', $payloadHash)->with('carriers')->first();
        if ($quote) {
            return $quote;
        }

        $uuid = Str::uuid()->toString();

        $freteRapidoResponse = $this->client->quote(
            $data['volumes'],
            $data['recipient']['address']['zipcode'],
            $data['simulation_type']
        );

        $quoteData = [
            'uuid'                  => $uuid,
            'payload_hash'          => $payloadHash,
            'recipient_zipcode'     => $data['recipient']['address']['zipcode'],
            'frete_rapido_request'  => json_encode($data),
            'frete_rapido_response' => json_encode($freteRapidoResponse),
            'response_time_ms'      => 0,
        ];

        $volumesData = collect($data['volumes'])->map(function ($volume) {
            return array_merge($volume, [
                'price' => $volume['unitary_price'] ?? 0,
            ]);
        })->toArray();

        // Cria a cotação e os volumes
        $quote = $this->quoteRepository->store($quoteData, $volumesData);

        // Processa os carriers e remove duplicados por chave composta
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
