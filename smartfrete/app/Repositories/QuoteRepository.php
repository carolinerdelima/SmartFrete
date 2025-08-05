<?php

namespace App\Repositories;

use App\Models\Quote;
use Illuminate\Support\Facades\DB;

class QuoteRepository
{
    public function store(array $quoteData, array $volumesData): Quote
    {
        return DB::transaction(function () use ($quoteData, $volumesData) {
            $quote = Quote::create($quoteData);
            $quote->volumes()->createMany($volumesData);
            return $quote;
        });
    }
}
