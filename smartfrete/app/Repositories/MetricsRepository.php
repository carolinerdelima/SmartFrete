<?php

namespace App\Repositories;

use App\Models\Carrier;
use Illuminate\Support\Facades\DB;

/**
 * Repositório responsável por agregar métricas relacionadas às cotações de frete.
 */
class MetricsRepository
{
    /**
     * Retorna métricas agregadas por transportadora, incluindo:
     * - Quantidade de cotações (quotes_count)
     * - Soma total dos fretes (total_freight)
     * - Média dos fretes (average_freight)
     *
     * Se o parâmetro $lastQuotes for fornecido, considera apenas as últimas N cotações.
     *
     * @param int|null $lastQuotes Número de últimas cotações a considerar (opcional)
     * @return array Lista de métricas por transportadora
     */
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

    /**
     * Retorna o frete mais barato entre as cotações armazenadas.
     *
     * @param int|null $lastQuotes Número de últimas cotações a considerar (opcional)
     * @return array|null Dados do frete mais barato ou null se não houver resultados
     */
    public function getCheapestFreight(?int $lastQuotes = null): ?array
    {
        return $this->getFreightExtreme('asc', $lastQuotes);
    }

    /**
     * Retorna o frete mais caro entre as cotações armazenadas.
     *
     * @param int|null $lastQuotes Número de últimas cotações a considerar (opcional)
     * @return array|null Dados do frete mais caro ou null se não houver resultados
     */
    public function getMostExpensiveFreight(?int $lastQuotes = null): ?array
    {
        return $this->getFreightExtreme('desc', $lastQuotes);
    }

    /**
     * Função auxiliar para retornar o frete mais barato ou mais caro,
     * dependendo do parâmetro $direction.
     *
     * @param string $direction 'asc' para mais barato, 'desc' para mais caro
     * @param int|null $lastQuotes Número de últimas cotações a considerar (opcional)
     * @return array|null Dados do frete extremo ou null se não houver resultados
     */
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
