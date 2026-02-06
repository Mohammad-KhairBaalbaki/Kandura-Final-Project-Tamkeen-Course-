<?php

namespace Database\Seeders;

use App\Models\DeviceToken;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DeviceTokenSeeder extends Seeder
{
    public function run(): void
    {
        $admins = User::whereHas('roles', function ($q) {
            $q->whereIn('name', ['admin', 'super-admin']);
        })->get();

        foreach ($admins as $admin) {
            DeviceToken::firstOrCreate(
                ['user_id' => $admin->id],
                ['token' => (string) Str::uuid()]
            );
        }
    }
}
