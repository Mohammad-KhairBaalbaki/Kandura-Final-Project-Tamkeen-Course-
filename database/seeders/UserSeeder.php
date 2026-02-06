<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {



        // $u1 = User::create([
        //         'name' => 'super admin',
        //         'email' => 'superadmin@gmail.com',
        //         'phone' => '0948593097',
        //         'password' => Hash::make(12345678),
        //     ]);

        //     $u1->assignRole('super-admin');
        //     $u2 = User::create([
        //         'name' => 'admin',
        //         'email' => 'admin@gmail.com',
        //         'phone' => '0994895148',
        //         'password' => Hash::make(12345678),
        //     ]);

        //     $u2->assignRole('admin');

        //     $u3 = User::create([
        //         'name' => 'user',
        //         'email' => 'user@gmail.com',
        //         'phone' => '0932430661',
        //         'password' => Hash::make(12345678),
        //     ]);
        //     $u3->assignRole('user');

        ////////////////////////////////////////////
        $u1 = User::withTrashed()->firstOrCreate(
            ['email' => 'superadmin@gmail.com'],
            [
                'name' => 'super admin',
                'phone' => '0948593097',
                'password' => Hash::make('12345678'),
                'is_active' => true,
            ]
        );

        $u1->assignRole('super-admin');
        if ($u1->trashed()) {
            $u1->restore();
        }

        $u2 = User::withTrashed()->firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'admin',
                'phone' => '0994895148',
                'password' => Hash::make('12345678'),
                'is_active' => true,
            ]
        );

        $u2->assignRole('admin');
        if ($u2->trashed()) {
            $u2->restore();
        }

        $u3 = User::withTrashed()->firstOrCreate(
            ['email' => 'user@gmail.com'],
            [
                'name' => 'user',
                'phone' => '0932430661',
                'password' => Hash::make('12345678'),
                'is_active' => true,
            ]
        );
        $u3->assignRole('user');
        if ($u3->trashed()) {
            $u3->restore();
        }

        $inactive = User::withTrashed()->firstOrCreate(
            ['email' => 'inactive_user@gmail.com'],
            [
                'name' => 'inactive user',
                'phone' => '0912345678',
                'password' => Hash::make('12345678'),
                'is_active' => false,
            ]
        );
        $inactive->assignRole('user');

        $deleted = User::withTrashed()->firstOrCreate(
            ['email' => 'deleted_user@gmail.com'],
            [
                'name' => 'deleted user',
                'phone' => '0923456789',
                'password' => Hash::make('12345678'),
                'is_active' => true,
            ]
        );
        $deleted->assignRole('user');
        if (!$deleted->trashed()) {
            $deleted->delete();
        }

        
    }
}
