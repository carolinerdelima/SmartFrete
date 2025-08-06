<?php

namespace App\Repositories;

use App\Models\Quote;
use Illuminate\Support\Facades\DB;

/**
 * Repositório responsável por operações de persistência relacionadas à entidade Quote.
 */
class QuoteRepository
{
    /**
     * Armazena uma nova cotação (Quote) com seus respectivos volumes em uma transação.
     *
     * @param array $quoteData      Dados da cotação a serem persistidos.
     * @param array $volumesData    Lista de volumes relacionados à cotação.
     * @return Quote                Instância da cotação criada, com volumes salvos.
     */
    public function store(array $quoteData, array $volumesData): Quote
    {
        return DB::transaction(function () use ($quoteData, $volumesData) {
            $quote = Quote::create($quoteData);
            $quote->volumes()->createMany($volumesData);
            return $quote;
        });
    }
}
