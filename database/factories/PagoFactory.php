<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Factura;
use App\Models\Pago;

class PagoFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Pago::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'factura_id' => Factura::factory(),
            'tipo_pago' => fake()->regexify('[A-Za-z0-9]{30}'),
            'monto_pagado' => fake()->randomFloat(2, 0, 99999999.99),
            'fecha_pago' => fake()->date(),
        ];
    }
}
