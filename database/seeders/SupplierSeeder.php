<?php

namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $suppliers = [
            ['name' => 'Distribuidora Norte', 'email' => 'norte@example.com', 'phone' => '555-0101'],
            ['name' => 'Pinturas del Valle', 'email' => 'valle@example.com', 'phone' => '555-0102'],
            ['name' => 'Electro Sur', 'email' => 'sur@example.com', 'phone' => '555-0103'],
        ];

        foreach ($suppliers as $supplier) {
            Supplier::create($supplier);
        }

        Supplier::factory()->count(2)->create();
    }
}
