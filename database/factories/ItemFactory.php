<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Item;
use App\Models\Location;
use App\Models\Supplier;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Item>
 */
class ItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->words(3, true),
            'description' => $this->faker->sentence(),
            'price' => $this->faker->randomFloat(2, 1, 100),
            'quantity' => $this->faker->numberBetween(1, 100),
            'category_id' => Category::query()->inRandomOrder()->value('id') ?? Category::factory(),
            'supplier_id' => Supplier::query()->inRandomOrder()->value('id') ?? Supplier::factory(),
            'location_id' => Location::query()->inRandomOrder()->value('id') ?? Location::factory(),
        ];
    }
}
