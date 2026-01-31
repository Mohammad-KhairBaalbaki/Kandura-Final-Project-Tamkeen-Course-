<?php

namespace App\Services\Web;

use App\Events\DashboardNotificationRequested;
use Illuminate\Http\Request;
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
            return Permission::orderBy('name')->get();
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
            $permissions = Permission::orderBy('name')->get();
            $role->load('permissions');
            return [
                'role' => $role,
                'permissions' => $permissions,
            ];
        });
    }

    public function update(Request $request, Role $role)
    {
        return DB::transaction(function () use ($request, $role) {
            if (in_array($role->name, ['user', 'super-admin'], true)) {
                abort(404);
            }
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255', 'unique:roles,name,' . $role->id],
                'permissions' => ['sometimes', 'array'],
                'permissions.*' => ['string', 'exists:permissions,name'],
            ]);

            $role->update([
                'name' => $validated['name'],
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
                        'url' => route('roles.show', $role->id)
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

