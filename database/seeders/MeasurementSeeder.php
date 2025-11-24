<?php

namespace Database\Seeders;

use App\Models\Measurement;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MeasurementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        Measurement::create([
            'size' => 'XS',
        ]);
        Measurement::create([
            'size' => 'S',
        ]);
        Measurement::create([
            'size' => 'M',
        ]);
        Measurement::create([
            'size' => 'L',
        ]);
        Measurement::create([
            'size' => 'XL',
        ]);
        Measurement::create([
            'size' => 'XXL',
        ]);
    }
}
