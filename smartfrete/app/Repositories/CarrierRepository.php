<?php

namespace App\Repositories;

use App\Models\Carrier;

class CarrierRepository
{
    public function upsertCarriers(array $carriersData): void
    {
        Carrier::upsert(
            $carriersData,
            ['quote_id', 'carrier_code', 'service_code'], // chaves únicas
            ['name', 'service', 'deadline_days', 'final_price', 'original_price']
        );
    }
}
