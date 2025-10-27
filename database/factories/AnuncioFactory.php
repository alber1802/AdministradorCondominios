<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Anuncio;
use App\Models\User;

class AnuncioFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Anuncio::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'titulo' => fake()->regexify('[A-Za-z0-9]{150}'),
            'contenido' => fake()->text(),
            'tipo' => fake()->regexify('[A-Za-z0-9]{50}'),
            'fecha_publicacion' => fake()->date(),
            'user_id' => User::factory(),
        ];
    }
}
