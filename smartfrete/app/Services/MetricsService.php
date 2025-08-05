<?php

namespace App\Services;

use App\Repositories\MetricsRepository;

class MetricsService
{
    public function __construct(
        protected MetricsRepository $repository
    ) {}

    public function getMetrics(?int $lastQuotes = null): array
    {
        return [
            'by_carrier'     => $this->repository->aggregateByCarrier($lastQuotes),
            'cheapest_freight' => $this->repository->getCheapestFreight($lastQuotes),
            'most_expensive_freight' => $this->repository->getMostExpensiveFreight($lastQuotes),
        ];
    }
}
