<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Pago;
use App\Models\PagoCripto;

class PagoCriptoFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PagoCripto::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'pago_id' => Pago::factory(),
            'wallet_origen' => fake()->regexify('[A-Za-z0-9]{120}'),
            'wallet_destino' => fake()->regexify('[A-Za-z0-9]{120}'),
            'moneda' => fake()->regexify('[A-Za-z0-9]{10}'),
            'hash_transaccion' => fake()->regexify('[A-Za-z0-9]{120}'),
        ];
    }
}
