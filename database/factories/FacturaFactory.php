<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Factura;
use App\Models\User;

class FacturaFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Factura::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'tipo' => fake()->regexify('[A-Za-z0-9]{50}'),
            'monto' => fake()->randomFloat(2, 0, 99999999.99),
            'estado' => fake()->regexify('[A-Za-z0-9]{20}'),
            'fecha_emision' => fake()->date(),
            'fecha_vencimiento' => fake()->date(),
            'qr_code' => fake()->word(),
            'comprobante_pdf' => fake()->word(),
            'descripcion' => fake()->word(),
        ];
    }
}
