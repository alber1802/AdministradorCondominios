<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Consumo;
use App\Models\Departamento;

class ConsumoFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Consumo::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'departamento_id' => Departamento::factory(),
            'tipo' => fake()->regexify('[A-Za-z0-9]{30}'),
            'lectura' => fake()->randomFloat(2, 0, 99999999.99),
            'unidad' => fake()->regexify('[A-Za-z0-9]{10}'),
            'fecha' => fake()->date(),
            'costo_unitario' => fake()->randomFloat(2, 0, 999999.99),
            'alerta' => fake()->boolean(),
        ];
    }
}
