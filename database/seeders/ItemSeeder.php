<?php

namespace Database\Seeders;

use App\Models\Item;
use Illuminate\Database\Seeder;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Item::create([
            'name' => 'Item 1',
            'description' => 'Description for Item 1',
            'price' => 10.99,
            'quantity' => 100,
        ]);

        Item::factory()->count(10)->create();
    }
}
