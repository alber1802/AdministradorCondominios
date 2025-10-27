<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\AreaComun;
use App\Models\AreasComune;
use App\Models\Reserva;
use App\Models\User;

class ReservaFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Reserva::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'area_id' => AreasComune::factory(),
            'user_id' => User::factory(),
            'fecha_inicio' => fake()->dateTime(),
            'fecha_fin' => fake()->dateTime(),
            'estado' => fake()->regexify('[A-Za-z0-9]{20}'),
            'qr_code' => fake()->word(),
            'area_comun_id' => AreaComun::factory(),
        ];
    }
}
