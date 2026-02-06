<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // $this->call(RolesAndPermissionsSeeder::class);
        // $this->call(UserSeeder::class);
        // $this->call(CitySeeder::class);
        // $this->call(AddressSeeder::class);
        // $this->call(MeasurementSeeder::class);
         $this->call(MainSeeder::class);
    }
}
