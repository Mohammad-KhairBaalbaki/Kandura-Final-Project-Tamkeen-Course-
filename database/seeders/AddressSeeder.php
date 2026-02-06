<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\City;
use App\Models\User;
use Illuminate\Database\Seeder;

class AddressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cities = City::all();
        $users = User::all();

        foreach ($users as $index => $user) {
            $cityId = $cities->random()?->id ?? City::factory()->create()->id;
            Address::firstOrCreate(
                [
                    'user_id' => $user->id,
                    'street' => 'Main Street '.$index,
                ],
                [
                    'city_id' => $cityId,
                    'details' => 'Primary address',
                    'is_default' => true,
                ]
            );
        }

        Address::factory()->count(20)->create();

        Address::inRandomOrder()->take(3)->get()->each(function (Address $address) {
            $address->delete();
        });
    }
}
