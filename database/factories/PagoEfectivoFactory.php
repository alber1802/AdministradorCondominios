<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Pago;
use App\Models\PagoEfectivo;
use App\Models\User;

class PagoEfectivoFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PagoEfectivo::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'pago_id' => Pago::factory(),
            'user_id' => User::factory(),
            'observacion' => fake()->text(),
        ];
    }
}
