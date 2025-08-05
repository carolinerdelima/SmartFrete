<?php

namespace App\Services;

use App\Models\Quote;

class MetricsService
{
    public function getMetrics(?int $lastQuotes): array
    {
        $query = Quote::with('carriers')->latest('id');

        if ($lastQuotes) {
            $query->limit($lastQuotes);
        }

        $quotes = $query->get();

        $carriers = $quotes->flatMap->carriers;

        return [
            'carriers' => $carriers->groupBy('name')->map(fn($group) => [
                'quantidade' => $group->count(),
                'soma_preco_frete' => $group->sum('final_price'),
                'media_preco_frete' => round($group->avg('final_price'), 2),
            ]),
            'frete_mais_barato' => $carriers->min('final_price'),
            'frete_mais_caro' => $carriers->max('final_price'),
        ];
    }
}
