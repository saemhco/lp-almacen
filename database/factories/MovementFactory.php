<?php

namespace Database\Factories;

use App\Models\Item;
use App\Models\Movement;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Movement>
 */
class MovementFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'item_id' => Item::query()->inRandomOrder()->value('id') ?? Item::factory(),
            'user_id' => User::query()->inRandomOrder()->value('id') ?? User::factory(),
            'type' => fake()->randomElement([Movement::ENTRADA, Movement::SALIDA]),
            'quantity' => fake()->numberBetween(1, 50),
            'note' => fake()->optional()->sentence(),
        ];
    }
}
