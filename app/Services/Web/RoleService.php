<?php

namespace App\Services\Web;

use App\Events\DashboardNotificationRequested;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleService
{
    public function index()
    {
        return DB::transaction(function () {
            return Role::whereNotIn('name', ['user', 'super-admin'])
                ->with('permissions')
                ->withCount('users')
                ->orderBy('name')
                ->paginate(15);
        });
    }

    public function create()
    {
        return DB::transaction(function () {
            $permissions = Permission::orderBy('name')
                ->whereNotIn('name', [
                    'create-orders',
                    'view-admins',
                    'add-admins',
                    'edit-admins',
                    'delete-admins',
                    'view-roles',
                    'add-roles',
                    'edit-roles',
                    'delete-roles',
                ])
                ->get();

            return [
                'permissions' => $permissions,
                'groupUsers' => $this->collectPermissions($permissions, ['view-users', 'disable-accounts']),
                'groupOrders' => $this->collectPermissions($permissions, ['view-orders', 'view-invoices', 'edit-orders']),
                'groupDesigns' => $this->collectPermissions($permissions, ['disable-designs']),
                'groupDesignOptions' => $this->collectPermissions($permissions, ['view-design-options', 'create-design-options', 'edit-design-options', 'delete-design-options']),
                'groupCoupons' => $this->collectPermissions($permissions, ['view-coupons', 'create-coupons', 'edit-coupons']),
                'groupWallets' => $this->collectPermissions($permissions, ['add-balance']),
                'groupNotifications' => $this->collectNotificationPermissions($permissions),
                'otherPermissions' => $this->collectOtherPermissions($permissions),
            ];
        });
    }

    public function store(Request $request)
    {
        return DB::transaction(function () use ($request) {
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255', 'unique:roles,name'],
                'permissions' => ['sometimes', 'array'],
                'permissions.*' => ['string', 'exists:permissions,name'],
            ]);

            $role = Role::create([
                'name' => $validated['name'],
                'guard_name' => 'api',
            ]);

            $role->syncPermissions($validated['permissions'] ?? []);

            return $role;
        });
    }

    public function show(Role $role)
    {
        return DB::transaction(function () use ($role) {
            if (in_array($role->name, ['user', 'super-admin'], true)) {
                abort(404);
            }
            $role->load('permissions');

            return $role;
        });
    }

    public function edit(Role $role)
    {
        return DB::transaction(function () use ($role) {
            if (in_array($role->name, ['user', 'super-admin'], true)) {
                abort(404);
            }
            $permissions = Permission::orderBy('name')
                ->whereNotIn('name', [
                    'create-orders',
                    'view-admins',
                    'add-admins',
                    'edit-admins',
                    'delete-admins',
                    'view-roles',
                    'add-roles',
                    'edit-roles',
                    'delete-roles',
                ])
                ->get();
            $role->load('permissions');

            return [
                'role' => $role,
                'permissions' => $permissions,
                'groupUsers' => $this->collectPermissions($permissions, ['view-users', 'disable-accounts']),
                'groupOrders' => $this->collectPermissions($permissions, ['view-orders', 'view-invoices', 'edit-orders']),
                'groupDesigns' => $this->collectPermissions($permissions, ['disable-designs']),
                'groupDesignOptions' => $this->collectPermissions($permissions, ['view-design-options', 'create-design-options', 'edit-design-options', 'delete-design-options']),
                'groupCoupons' => $this->collectPermissions($permissions, ['view-coupons', 'create-coupons', 'edit-coupons']),
                'groupWallets' => $this->collectPermissions($permissions, ['add-balance']),
                'groupNotifications' => $this->collectNotificationPermissions($permissions),
                'otherPermissions' => $this->collectOtherPermissions($permissions),
            ];
        });
    }

    protected function collectPermissions(Collection $permissions, array $names): Collection
    {
        $permissionsByName = $permissions->keyBy('name');

        return collect($names)
            ->filter(fn ($name) => $permissionsByName->has($name))
            ->map(fn ($name) => $permissionsByName[$name])
            ->values();
    }

    protected function collectNotificationPermissions(Collection $permissions): Collection
    {
        return $permissions
            ->filter(fn ($p) => str_starts_with($p->name, 'notify.'))
            ->values();
    }

    protected function collectOtherPermissions(Collection $permissions): Collection
    {
        $used = collect([
            'view-users', 'disable-accounts',
            'view-orders', 'view-invoices', 'edit-orders',
            'create-designs', 'edit-designs', 'delete-designs', 'disable-designs',
            'view-design-options', 'create-design-options', 'edit-design-options', 'delete-design-options',
            'view-coupons', 'create-coupons', 'edit-coupons',
            'add-reviews',
            'add-balance',
        ]);

        $notifications = $this->collectNotificationPermissions($permissions)->pluck('name');
        $used = $used->merge($notifications)->unique();

        return $permissions->filter(fn ($p) => ! $used->contains($p->name))->values();
    }

    public function update(array $data, Role $role)
    {
        return DB::transaction(function () use ($data, $role) {
            if (in_array($role->name, ['user', 'super-admin'], true)) {
                abort(404);
            }


            $role->update([
                'name' => $data['name'],
                'guard_name' => 'api',
            ]);

            $role->syncPermissions($validated['permissions'] ?? []);

            // send notification to super admin when roles is edited
            event(new DashboardNotificationRequested(
                'notify.admin.permissions.updated',
                'Role Permissions Updated',
                "Role $role->name (#{$role->id}) Permissions was Updated",
                [
                    'type' => 'super.admin',
                    'event' => 'permissions updated',
                    'admin_id' => $role->id,
                    'url' => route('roles.show', $role->id),
                ]
            ));

            return $role;
        });
    }

    public function destroy(Role $role)
    {
        return DB::transaction(function () use ($role) {
            if (in_array($role->name, ['user', 'super-admin'], true)) {
                abort(404);
            }
            if ($role->users()->count() > 0) {
                return [
                    'error' => 'role_has_users',
                ];
            }
            $role->delete();

            return true;
        });
    }
}
