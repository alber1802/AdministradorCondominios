<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Pago;
use App\Models\PagoTigoMoney;

class PagoTigoMoneyFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PagoTigoMoney::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'pago_id' => Pago::factory(),
            'numero_telefono' => fake()->regexify('[A-Za-z0-9]{20}'),
            'referencia_transaccion' => fake()->word(),
        ];
    }
}
