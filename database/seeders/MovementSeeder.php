<?php

namespace Database\Seeders;

use App\Models\Item;
use App\Models\Movement;
use App\Models\User;
use Illuminate\Database\Seeder;

class MovementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userId = User::query()->value('id');

        Item::query()->each(function (Item $item) use ($userId) {
            Movement::create([
                'item_id' => $item->id,
                'user_id' => $userId,
                'type' => Movement::ENTRADA,
                'quantity' => $item->quantity,
                'note' => 'Carga inicial',
            ]);
        });

        Movement::factory()->count(15)->create();
    }
}
