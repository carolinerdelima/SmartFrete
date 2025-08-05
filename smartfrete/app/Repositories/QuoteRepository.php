<?php

namespace App\Repositories;

use App\Models\Quote;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class QuoteRepository
{
    public function store(array $quoteData, array $volumesData, array $carriers): Quote
    {
        return DB::transaction(function () use ($quoteData, $volumesData, $carriers) {
            $quote = Quote::create($quoteData);
            $quote->volumes()->createMany($volumesData);
            $quote->carriers()->createMany($carriers);
            return $quote;
        });
    }
}
