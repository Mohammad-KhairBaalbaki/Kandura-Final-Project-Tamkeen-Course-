<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $u1 = User::create([
            'name' => 'super admin',
            'email' => 'superadmin@gmail.com',
            'phone' => '0111111111',
            'password' => Hash::make(12345)
        ]);

        $u1->assignRole('super-admin');
        $u2 = User::create([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'phone' => '0222222222',
            'password' => Hash::make(12345)
        ]);
        $u2->assignRole('admin');

        $u3 = User::create([
            'name' => 'user',
            'email' => 'user@gmail.com',
            'phone' => '0333333333',
            'password' => Hash::make(12345)
        ]);
        $u3->assignRole('user');

    }
}
