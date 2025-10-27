<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\AreaComun;

class AreaComunFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = AreaComun::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'nombre' => fake()->regexify('[A-Za-z0-9]{7}'),
            'descripcion' => fake()->text(),
            'capacidad' => fake()->numberBetween(0, 15),
            'disponibilidad' => fake()->boolean(),
        ];
    }
}
