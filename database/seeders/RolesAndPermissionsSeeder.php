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
        $guard = 'api';

        $superAdmin = Role::firstOrCreate(['name' => 'super-admin', 'guard_name' => $guard]);

        $admin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => $guard]);

        $user = Role::firstOrCreate(['name' => 'user', 'guard_name' => $guard]);

        $allPermissions = [
            // Orders & invoices
            'view-orders',
            'view-invoices',
            'create-orders',
            'edit-orders',
            // Users
            'view-users',
            'disable-accounts',
            // Admins (web + api)
            'view-admins',
            'add-admins',
            'edit-admins',
            'delete-admins',
            // Roles
            'view-roles',
            'add-roles',
            'edit-roles',
            'delete-roles',
            // Designs
            'create-designs',
            'edit-designs',
            'delete-designs',
            'disable-designs',
            // Design options
            'view-design-options',
            'create-design-options',
            'edit-design-options',
            'delete-design-options',
            // Coupons
            'view-coupons',
            'create-coupons',
            'edit-coupons',
            // Reviews
            'add-reviews',
            // Wallets
            'add-balance',
        ];

        foreach ($allPermissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => $guard]);
        }

        $userPermissions = [
            'create-orders',
            'view-invoices',
            'create-designs',
            'edit-designs',
            'delete-designs',
            'add-reviews',
        ];

        $adminPermissions = [
            'view-users',
            'disable-accounts',
            'view-orders',
            'view-invoices',
            'view-design-options',
            'create-design-options',
            'edit-design-options',
            'delete-design-options',
            'view-coupons',
            'create-coupons',
            'edit-coupons',
            'add-balance',
            'disable-designs',
        ];

        // //////////////////////////////
        $SuperAdminNotificationPermissions = [
            'notify.admin.created',
            'notify.admin.removed',
            'notify.admin.permissions.updated',
        ];

        $adminNotificationPermissions = [
            'notify.orders.created',
            'notify.orders.cancelled',
            'notify.orders.issue',
            'notify.users.registered',
            'notify.users.deactivated',
            'notify.designs.created',
            'notify.designs.updated',
        ];

        foreach ($SuperAdminNotificationPermissions as $p) {
            Permission::firstOrCreate(['name' => $p, 'guard_name' => 'api']);
        }

        foreach ($adminNotificationPermissions as $p) {
            Permission::firstOrCreate(['name' => $p, 'guard_name' => 'api']);
        }
        // //////////////////////////
        $user->syncPermissions($userPermissions);
        $admin->syncPermissions(array_merge($adminNotificationPermissions, $adminPermissions));
        $superAdmin->syncPermissions(array_merge($allPermissions, $SuperAdminNotificationPermissions, $adminNotificationPermissions));
    }
}
