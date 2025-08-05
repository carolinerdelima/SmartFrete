<?php

namespace App\Services;

use App\Http\Clients\FreteRapidoHttpClient;
use App\Repositories\QuoteRepository;
use Illuminate\Support\Str;

class QuoteService
{
    public function __construct(
        protected FreteRapidoHttpClient $client,
        protected QuoteRepository $repository
    ) {}

    public function createQuote(array $data)
    {
        $uuid = Str::uuid()->toString();

        $freteRapidoResponse = $this->client->quote(
            $data['volumes'],
            $data['recipient']['address']['zipcode'],
            $data['simulation_type']
        );

        $quoteData = [
            'uuid'                  => $uuid,
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

        $carriersData = collect(data_get($freteRapidoResponse, 'dispatchers.0.offers', []))
            ->map(function ($item) {
                return [
                    'name'           => data_get($item, 'carrier.name'),
                    'service'        => data_get($item, 'service'),
                    'deadline_days'  => data_get($item, 'delivery_time.days'),
                    'final_price'    => data_get($item, 'final_price'),
                    'original_price' => data_get($item, 'cost_price'),
                    'carrier_code'   => data_get($item, 'carrier.reference'),
                    'service_code'   => data_get($item, 'service_code'),
                ];
            })->toArray();

        return $this->repository->store($quoteData, $volumesData, $carriersData);
    }

}
