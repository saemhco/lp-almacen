<?php

namespace Database\Seeders;

use App\Models\Location;
use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $locations = [
            ['name' => 'Pasillo A', 'code' => 'A-01'],
            ['name' => 'Pasillo B', 'code' => 'B-02'],
            ['name' => 'Bodega principal', 'code' => 'BOD-01'],
        ];

        foreach ($locations as $location) {
            Location::create($location);
        }

        Location::factory()->count(2)->create();
    }
}
