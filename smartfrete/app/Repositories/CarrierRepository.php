<?php

namespace App\Repositories;

use App\Models\Carrier;

/**
 * Repositório responsável por operações relacionadas ao model Carrier.
 */
class CarrierRepository
{
    /**
     * Insere ou atualiza dados de transportadoras (carriers) no banco de dados.
     *
     * Caso já exista um registro com a combinação de `quote_id`, `carrier_code` e `service_code`,
     * os campos `name`, `service`, `deadline_days`, `final_price` e `original_price` serão atualizados.
     *
     * @param array $carriersData Dados das transportadoras a serem inseridas ou atualizadas.
     * 
     * @return void
     */
    public function upsertCarriers(array $carriersData): void
    {
        Carrier::upsert(
            $carriersData,
            ['quote_id', 'carrier_code', 'service_code'], // chaves únicas
            ['name', 'service', 'deadline_days', 'final_price', 'original_price']
        );
    }
}
