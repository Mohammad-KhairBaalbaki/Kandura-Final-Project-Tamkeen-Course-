<?php

namespace App\Services\Web;

use App\Events\DashboardNotificationRequested;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use App\Services\Global\AdminService as CoreAdminService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Models\Role;

class AdminService
{
    protected $adminService;

    public function __construct(CoreAdminService $adminService)
    {
        $this->adminService = $adminService;
    }

    public function index(Request $request)
    {
        return DB::transaction(function () use ($request) {
            $query = User::query()->whereHas('roles', function ($q) {
                $q->where('name', 'admin');
            });

            if (isset($request->search)) {
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
                'total_users' => User::whereHas('roles', function ($q) {
                    $q->where('name', 'admin');
                })->count(),
                'active_users' => User::whereHas('roles', function ($q) {
                    $q->where('name', 'admin');
                })->where('is_active', true)->count(),
                'new_users' => User::whereHas('roles', function ($q) {
                    $q->where('name', 'admin');
                })->whereMonth('created_at', now()->month)->count(),
                'blocked_users' => User::whereHas('roles', function ($q) {
                    $q->where('name', 'admin');
                })->where('is_active', false)->count(),
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
            if (!$user->hasRole('admin')) {
                abort(404);
            }

            $user->load([
                'image',
            ]);

            return $user;
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

    public function store(RegisterRequest $request)
    {
        return DB::transaction(function () use ($request) {
            return $this->adminService->storeAdmin($request->validated());
        });
    }

    public function edit(User $user)
    {
        return DB::transaction(function () use ($user) {
            if (!$user->hasRole('admin')) {
                abort(404);
            }

            $roles = Role::whereNotIn('name', ['user', 'super-admin'])
                ->orderBy('name')
                ->pluck('name');

            return [
                'admin' => $user,
                'roles' => $roles,
            ];
        });
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        return DB::transaction(function () use ($request, $user) {

            $data = $request->validated();
            if ($request->filled('new_password')) {
                $currentUser = Auth::user();
                if (!$currentUser || !$currentUser->hasRole('super-admin')) {
                    throw ValidationException::withMessages([
                        'super_admin_password' => __('admins.not_authorized_edit_admin'),
                    ]);
                }

                if (!Hash::check($request->input('super_admin_password'), $currentUser->password)) {
                    throw ValidationException::withMessages([
                        'super_admin_password' => __('auth.password'),
                    ]);
                }

                $data['password'] = $request->input('new_password');
            }

            unset($data['new_password'], $data['new_password_confirmation'], $data['super_admin_password']);

            return $this->adminService->updateAdmin($data, $user);
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
                    'url' => route('admins.index')
                ]
            ));
            $user->delete();
            return true;
        });
    }
}

