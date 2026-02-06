<?php

namespace Database\Seeders;

use App\Models\NotificationPreference;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class NotificationPreferenceSeeder extends Seeder
{
    public function run(): void
    {
        $notifyPermissions = Permission::where('name', 'like', 'notify.%')->get();
        if ($notifyPermissions->isEmpty()) {
            return;
        }

        $admins = User::whereHas('roles', function ($q) {
            $q->whereIn('name', ['admin', 'super-admin']);
        })->get();

        foreach ($admins as $admin) {
            foreach ($notifyPermissions as $perm) {
                NotificationPreference::firstOrCreate(
                    [
                        'user_id' => $admin->id,
                        'permission_id' => $perm->id,
                    ],
                    [
                        'enabled' => (bool) random_int(0, 1),
                    ]
                );
            }
        }
    }
}
