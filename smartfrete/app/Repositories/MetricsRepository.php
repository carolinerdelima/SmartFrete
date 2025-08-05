<?php

namespace App\Repositories;

use App\Models\Carrier;
use Illuminate\Support\Facades\DB;

class MetricsRepository
{
    public function aggregateByCarrier(?int $lastQuotes = null): array
    {
        $query = Carrier::select(
                'name as carrier',
                DB::raw('COUNT(*) as quotes_count'),
                DB::raw('SUM(final_price) as total_freight'),
                DB::raw('AVG(final_price) as average_freight')
            )
            ->join('quotes', 'carriers.quote_id', '=', 'quotes.id')
            ->groupBy('name')
            ->orderByDesc('quotes_count');

        if ($lastQuotes) {
            $query->whereIn('quote_id', function ($subquery) use ($lastQuotes) {
                $subquery->select('id')
                         ->from('quotes')
                         ->orderByDesc('created_at')
                         ->limit($lastQuotes);
            });
        }

        return $query->get()->toArray();
    }

    public function getCheapestFreight(?int $lastQuotes = null): ?array
    {
        return $this->getFreightExtreme('asc', $lastQuotes);
    }

    public function getMostExpensiveFreight(?int $lastQuotes = null): ?array
    {
        return $this->getFreightExtreme('desc', $lastQuotes);
    }

    protected function getFreightExtreme(string $direction, ?int $lastQuotes = null): ?array
    {
        $query = Carrier::select(
                'name as carrier',
                'service as service',
                'final_price as freight_price',
                'deadline_days as delivery_days'
            )
            ->join('quotes', 'carriers.quote_id', '=', 'quotes.id')
            ->orderBy('final_price', $direction)
            ->limit(1);

        if ($lastQuotes) {
            $query->whereIn('quote_id', function ($subquery) use ($lastQuotes) {
                $subquery->select('id')
                         ->from('quotes')
                         ->orderByDesc('created_at')
                         ->limit($lastQuotes);
            });
        }

        return $query->first()?->toArray();
    }
}
