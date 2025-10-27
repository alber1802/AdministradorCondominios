<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Departamento;
use App\Models\User;

class DepartamentoFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Departamento::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'numero' => fake()->regexify('[A-Za-z0-9]{100}'),
            'piso' => fake()->regexify('[A-Za-z0-9]{50}'),
            'bloque' => fake()->regexify('[A-Za-z0-9]{50}'),
            'user_id' => User::factory(),
        ];
    }
}
