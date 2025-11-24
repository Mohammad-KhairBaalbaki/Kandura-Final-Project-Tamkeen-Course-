<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        //////////////////////////////

        //User      (Registered user who makes purchases and designs)
        $user = Role::create(['name' => 'user', 'guard_name' => 'api']);
        $userPermissions = [

            'create-design',
            'edit-design',
            'delete-design',

            'create-orders',
            'add-review',
        ];
        foreach ($userPermissions as $per) {
            Permission::create(['name' => $per, 'guard_name' => 'api']);
        }

        $user->givePermissionTo($userPermissions);

        //////////////////////////////////////////
        // Admin     (Staff member managing orders and content)

        $admin = Role::create(['name' => 'admin','guard_name' => 'api']);

        $adminPermissions = [
            'view-users',
            'disable-accounts',

            'create-coupon',
            'edit-coupon',
            'delete-coupon',

            'create-design-option',
            'edit-design-option',
            'delete-design-option',

            'view-reviews',
            'approuve-and-reject-reviews',
            'send-notifications',
            'add-balance',

        ];

        foreach ($adminPermissions as $per) {
            Permission::create(['name' => $per, 'guard_name' => 'api']);
        }

        $admin->givePermissionTo($adminPermissions);
        // ////////////////////////////////////////////
        //Super Admin   ( Top administrator with full system permissions)
        $superAdmin = Role::create(['name' => 'super-admin', 'guard_name' => 'api']);

        $superAdminPermissions = [
            'add-admin',
            'edit-admin',
            'delete-admin',

            'view-reports-and-statistics',
            'add-role',
            'edit-role',
            'delete-role'
        ];
        foreach ($superAdminPermissions as $per) {
            Permission::create(['name' => $per, 'guard_name' => 'api']);
        }
        $superAdmin->givePermissionTo($adminPermissions);
        $superAdmin->givePermissionTo($superAdminPermissions);









    }
}
