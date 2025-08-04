<?php

namespace App\Services;

use App\Models\Quote;
use App\Models\Volume;
use App\Models\Carrier;
use App\Http\Clients\FreteRapidoHttpClient;
use Illuminate\Support\Facades\DB;

class QuoteService
{
    public function __construct(protected FreteRapidoHttpClient $freteRapidoClient)
    {
    }

    public function createQuote(array $data): Quote
    {
        return DB::transaction(function () use ($data) {
            $zipcode = $data['recipient']['address']['zipcode'];
            $volumes = $data['volumes'];

            // Prepara volumes no formato da API
            $volumesApi = collect($volumes)->map(fn ($v) => [
                'category'       => $v['category'],
                'amount'         => $v['amount'],
                'unitary_weight' => $v['unitary_weight'],
                'unitary_price'  => $v['price'],
                'sku'            => $v['sku'],
                'height'         => $v['height'],
                'width'          => $v['width'],
                'length'         => $v['length'],
            ])->toArray();

            $start = microtime(true);
            $response = $this->freteRapidoClient->quote($volumesApi, $zipcode);
            $duration = intval((microtime(true) - $start) * 1000);

            $quote = Quote::create([
                'recipient_zipcode'     => $zipcode,
                'frete_rapido_request'  => $data,
                'frete_rapido_response' => $response,
                'response_time_ms'      => $duration,
                'status'                => 'success',
            ]);

            foreach ($volumes as $v) {
                $quote->volumes()->create($v);
            }

            $offers = data_get($response, 'dispatchers.0.offers', []);
            foreach ($offers as $oferta) {
                $quote->carriers()->create([
                    'name'           => data_get($oferta, 'carrier.name'),
                    'service'        => data_get($oferta, 'service'),
                    'deadline_days'  => data_get($oferta, 'delivery_time.days'),
                    'final_price'    => data_get($oferta, 'final_price'),
                    'original_price' => data_get($oferta, 'cost_price'),
                    'carrier_code'   => data_get($oferta, 'carrier.reference'),
                    'service_code'   => data_get($oferta, 'service_code'),
                ]);
            }

            return $quote;
        });
    }
}
