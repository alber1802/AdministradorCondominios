<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Nomina;
use App\Models\User;

class NominaFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Nomina::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'mes' => fake()->regexify('[A-Za-z0-9]{20}'),
            'monto' => fake()->randomFloat(2, 0, 99999999.99),
            'estado' => fake()->regexify('[A-Za-z0-9]{20}'),
            'fecha_pago' => fake()->date(),
            'comprobante_pdf' => fake()->word(),
        ];
    }
}
