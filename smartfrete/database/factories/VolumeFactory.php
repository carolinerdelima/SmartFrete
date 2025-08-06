<?php

namespace Database\Factories;

use App\Models\Quote;
use App\Models\Volume;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Factory para gerar instâncias fake de Volume.
 *
 * Utilizada em testes e seeders para simular volumes associados a cotações.
 */
class VolumeFactory extends Factory
{
    /**
     * Define o modelo associado à factory.
     *
     * @var class-string<\App\Models\Volume>
     */
    protected $model = Volume::class;

    /**
     * Define os atributos padrões para uma instância de Volume.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'quote_id'       => Quote::factory(), // Cria quote associada automaticamente
            'category'       => $this->faker->numberBetween(1, 10),
            'amount'         => $this->faker->numberBetween(1, 5),
            'unitary_weight' => $this->faker->randomFloat(2, 0.1, 30.0), // Peso em kg
            'price'          => $this->faker->randomFloat(2, 10, 1000),
            'sku'            => strtoupper($this->faker->bothify('ABC-###-???')),
            'height'         => $this->faker->randomFloat(2, 0.1, 1.0), // Altura em metros
            'width'          => $this->faker->randomFloat(2, 0.1, 1.0), // Largura em metros
            'length'         => $this->faker->randomFloat(2, 0.1, 1.0), // Comprimento em metros
        ];
    }
}
