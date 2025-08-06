<?php

namespace Database\Factories;

use App\Models\Carrier;
use App\Models\Quote;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Factory para geração de instâncias de Carrier com dados fake.
 *
 * Utilizada em testes e seeders.
 */
class CarrierFactory extends Factory
{
    /**
     * Define o modelo associado à factory.
     *
     * @var string
     */
    protected $model = Carrier::class;

    /**
     * Define os atributos padrões para uma instância de Carrier.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'quote_id'       => Quote::factory(), // cria automaticamente uma quote associada
            'name'           => $this->faker->randomElement(['Correios', 'EXPRESSO FR', 'Loggi', 'FedEx']),
            'service'        => $this->faker->randomElement(['SEDEX', 'PAC', 'Rodoviário']),
            'deadline_days'  => $this->faker->numberBetween(1, 10),
            'final_price'    => $this->faker->randomFloat(2, 10, 100),
            'carrier_code'   => $this->faker->bothify('CRR-###'),
            'service_code'   => $this->faker->bothify('SRV-###'),
            'original_price' => $this->faker->randomFloat(2, 15, 120),
        ];
    }
}
