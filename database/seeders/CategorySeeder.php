<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Ferreteria', 'description' => 'Tornillos, herramientas y fijaciones'],
            ['name' => 'Pinturas', 'description' => 'Pinturas, solventes y brochas'],
            ['name' => 'Electricidad', 'description' => 'Cables, focos e interruptores'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }

        Category::factory()->count(2)->create();
    }
}
