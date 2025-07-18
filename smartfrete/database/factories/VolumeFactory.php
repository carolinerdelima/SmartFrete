<?php

namespace Database\Factories;

use App\Models\Quote;
use App\Models\Volume;
use Illuminate\Database\Eloquent\Factories\Factory;

class VolumeFactory extends Factory
{
    protected $model = Volume::class;

    public function definition(): array
    {
        return [
            'quote_id'       => Quote::factory(), // cria quote associada automaticamente
            'category'       => $this->faker->numberBetween(1, 10),
            'amount'         => $this->faker->numberBetween(1, 5),
            'unitary_weight' => $this->faker->randomFloat(2, 0.1, 30.0), // em kg
            'price'          => $this->faker->randomFloat(2, 10, 1000),
            'sku'            => strtoupper($this->faker->bothify('ABC-###-???')),
            'height'         => $this->faker->randomFloat(2, 0.1, 1.0), // metros
            'width'          => $this->faker->randomFloat(2, 0.1, 1.0),
            'length'         => $this->faker->randomFloat(2, 0.1, 1.0),
        ];
    }
}
