<?php

namespace App\Services\Global;

use App\Events\DashboardNotificationRequested;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AdminService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function storeAdmin(array $data)
    {
        return DB::transaction(function () use ($data) {


            $roles = $data['roles'] ?? ['admin'];
            $roles = array_values(array_diff($roles, ['user', 'super-admin']));
            unset($data['roles']);
            $user = User::create($data);
            $user->syncRoles($roles);

            //send notification to super admin
            event(new DashboardNotificationRequested(
                'notify.admin.created',
                'Admin created',
                "Admin (user #{$user->id}) was created",
                [
                    'type' => 'super.admin',
                    'event' => 'created',
                    'admin_id' => $user->id,
                    'url' => route('admins.show', $user->id)
                ]
            ));

            return $user;
        });
    }

    public function updateAdmin(array $data, User $user)
    {
        return DB::transaction(function () use ($data, $user) {


            $roles = $data['roles'] ?? null;
            if ($roles !== null) {
                $roles = array_values(array_diff($roles, ['user', 'super-admin']));
            }
            unset($data['roles']);
            $user->update($data);
            if ($roles !== null) {
                $user->syncRoles($roles);
                // send notification to super admin when roles is edited
                event(new DashboardNotificationRequested(
                    'notify.admin.permissions.updated',
                    'Admin Permissions Updated',
                    "Admin $user->name (user #{$user->id}) Permissions was Updated",
                    [
                        'type' => 'super.admin',
                        'event' => 'permissions updated',
                        'admin_id' => $user->id,
                        'url' => route('admins.show', $user->id)
                    ]
                ));
            }
            $user = User::findOrFail($user->id);
            return $user;
        });
    }

    public function deleteAdmin(User $user)
    {
        return DB::transaction(function () use ($user) {
            $user->delete();
            return true;
        });
    }
}


