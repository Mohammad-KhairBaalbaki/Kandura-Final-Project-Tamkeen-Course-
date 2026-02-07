<?php

namespace App\Services\Web;

use App\Events\DashboardNotificationRequested;
use App\Models\User;
use App\Services\Global\AdminService as CoreAdminService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Models\Role;

class AdminService
{

    public function index(Request $request)
    {
        return DB::transaction(function () use ($request) {
            $baseQuery = User::whereHas('roles', function ($q) {
                $q->whereNotIn('name', ['super-admin', 'user']);
            });

            $totalUsers = (clone $baseQuery)->count();
            $activeUsers = (clone $baseQuery)->where('is_active', true)->count();
            $newUsers = (clone $baseQuery)->whereMonth('created_at', now()->month)->count();
            $blockedUsers = (clone $baseQuery)->where('is_active', false)->count();

            $query = clone $baseQuery;

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                });
            }

            if (isset($request->is_active)) {
                $query->where('is_active', $request->is_active);
            }

            $users = $query->with('image')
                ->latest()
                ->paginate(15);
            $stats = [
                'total_users' => $totalUsers,
                'active_users' => $activeUsers,
                'new_users' => $newUsers,
                'blocked_users' => $blockedUsers,
            ];
            return [
                'users' => $users,
                'stats' => $stats,
            ];
        });
    }

    public function show(User $user)
    {
        return DB::transaction(function () use ($user) {
            $user->load([
                'image',
                'roles.permissions',
            ]);

            return $user;
        });
    }

    public function trashed(Request $request)
    {
        return DB::transaction(function () use ($request) {
            $query = User::onlyTrashed()
                ->whereHas('roles', function ($q) {
                    $q->whereNotIn('name', ['super-admin', 'user']);
                })
                ->with('image');

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                });
            }

            return $query->latest('deleted_at')->paginate(15)->withQueryString();
        });
    }

    public function create()
    {
        return DB::transaction(function () {
            return Role::whereNotIn('name', ['user', 'super-admin'])
                ->orderBy('name')
                ->pluck('name');
        });
    }

    public function store($data)
    {
        return DB::transaction(function () use ($data) {

            $roles = $data['roles'] ?? ['admin'];
            $roles = array_values(array_diff($roles, ['user', 'super-admin']));
            unset($data['roles']);
            $user = User::create($data);
            $user->syncRoles($roles);

            // send notification to super admin
            event(new DashboardNotificationRequested(
                'notify.admin.created',
                'Admin created',
                "Admin (user #{$user->id}) was created",
                [
                    'type' => 'super.admin',
                    'event' => 'created',
                    'admin_id' => $user->id,
                    'url' => route('admins.show', $user->id),
                ]
            ));

            return $user;
        });
    }

    public function edit(User $user)
    {
        return DB::transaction(function () use ($user) {

            $roles = Role::whereNotIn('name', ['user', 'super-admin'])
                ->orderBy('name')
                ->pluck('name');

            return [
                'admin' => $user,
                'roles' => $roles,
            ];
        });
    }

    public function update(array $data, User $user)
    {
        return DB::transaction(function () use ($data, $user) {

            if ($data('new_password')) {
                $currentUser = Auth::user();
                if (!$currentUser || !$currentUser->hasRole('super-admin')) {
                    throw ValidationException::withMessages([
                        'super_admin_password' => __('admins.not_authorized_edit_admin'),
                    ]);
                }

                if (!Hash::check($data('super_admin_password'), $currentUser->password)) {
                    throw ValidationException::withMessages([
                        'super_admin_password' => __('auth.password'),
                    ]);
                }

                $data['password'] = $data('new_password');
            }

            unset($data['new_password'], $data['new_password_confirmation'], $data['super_admin_password']);

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
                        'url' => route('admins.show', $user->id),
                    ]
                ));
            }
            $user = User::findOrFail($user->id);

            return $user;
        });
    }

    public function delete(User $user)
    {
        return DB::transaction(function () use ($user) {
            event(new DashboardNotificationRequested(
                'notify.admin.removed',
                'Admin Deleted',
                "Admin (user #{$user->id}) was deleted",
                [
                    'type' => 'super.admin',
                    'event' => 'removed',
                    'admin_id' => $user->id,
                    'url' => route('admins.index'),
                ]
            ));
            $user->delete();

            return true;
        });
    }

    public function restore(int $userId): bool
    {
        return DB::transaction(function () use ($userId) {
            $user = User::onlyTrashed()
                ->whereHas('roles', function ($q) {
                    $q->whereNotIn('name', ['super-admin', 'user']);
                })
                ->findOrFail($userId);

            return (bool) $user->restore();
        });
    }
}
