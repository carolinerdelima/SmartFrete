<?php

namespace App\Services;

use App\Repositories\MetricsRepository;

/**
 * Service responsável por obter métricas relacionadas às cotações armazenadas.
 */
class MetricsService
{
    /**
     * Cria uma nova instância da service de métricas.
     *
     * @param MetricsRepository $repository Repositório responsável por consultas de métricas.
     */
    public function __construct(
        protected MetricsRepository $repository
    ) {}

    /**
     * Retorna métricas agregadas sobre as cotações.
     *
     * @param int|null $lastQuotes Quantidade de últimas cotações a considerar (opcional).
     * @return array {
     *     @type array $by_carrier Lista de métricas agrupadas por transportadora.
     *     @type array|null $cheapest_freight Dados do frete mais barato.
     *     @type array|null $most_expensive_freight Dados do frete mais caro.
     * }
     */
    public function getMetrics(?int $lastQuotes = null): array
    {
        return [
            'by_carrier'             => $this->repository->aggregateByCarrier($lastQuotes),
            'cheapest_freight'       => $this->repository->getCheapestFreight($lastQuotes),
            'most_expensive_freight' => $this->repository->getMostExpensiveFreight($lastQuotes),
        ];
    }
}
