<?php

namespace Database\Seeders;

use App\Models\City;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Symfony\Component\Console\Question\ConfirmationQuestion;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        // City::factory(3)->create();

        City::create([
            'name' => [
                'en' => 'Cairo',
                'ar' => 'مصر'
            ]
        ]);
    }
}
