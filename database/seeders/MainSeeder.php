<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class MainSeeder extends Seeder
{
    /**
     * Seed the application's database with a full dataset.
     */
    public function run(): void
    {
        $this->call([
            RolesAndPermissionsSeeder::class,
            UserSeeder::class,
            CitySeeder::class,
            AddressSeeder::class,
            MeasurementSeeder::class,
            DesignOptionSeeder::class,
            DesignSeeder::class,
            CouponSeeder::class,
            WalletSeeder::class,
            OrderSeeder::class,
            NotificationPreferenceSeeder::class,
            DeviceTokenSeeder::class,
        ]);
    }
}
