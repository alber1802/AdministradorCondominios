<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Ticket;
use App\Models\User;

class TicketFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Ticket::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'titulo' => fake()->regexify('[A-Za-z0-9]{150}'),
            'descripcion' => fake()->text(),
            'estado' => fake()->regexify('[A-Za-z0-9]{30}'),
            'prioridad' => fake()->regexify('[A-Za-z0-9]{20}'),
            'tecnico_id' => User::factory(),
        ];
    }
}
