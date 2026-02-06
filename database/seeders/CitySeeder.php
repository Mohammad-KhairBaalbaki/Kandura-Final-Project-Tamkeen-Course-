<?php

namespace Database\Seeders;

use App\Models\City;
use Illuminate\Database\Seeder;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cities = [
            ['en' => 'Cairo', 'ar' => 'القاهرة'],
            ['en' => 'Dubai', 'ar' => 'دبي'],
            ['en' => 'Abu Dhabi', 'ar' => 'أبو ظبي'],
            ['en' => 'Sharjah', 'ar' => 'الشارقة'],
        ];

        foreach ($cities as $city) {
            City::firstOrCreate(
                ['name->en' => $city['en']],
                ['name' => $city]
            );
        }
    }
}
