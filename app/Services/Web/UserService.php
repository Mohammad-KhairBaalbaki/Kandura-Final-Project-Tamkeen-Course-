<?php

namespace App\Services\Web;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserService
{
    public function index(Request $request)
    {
        return DB::transaction(function () use ($request) {
            $query = User::query()->whereHas('roles', function ($q) {
                $q->where('name', 'user');
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

            $users = $query->withCount('orders')
                ->withCount('designs')
                ->with('image')
                ->latest()
                ->paginate(15);

            $stats = [
                'total_users' => User::whereHas('roles', function ($q) {
                    $q->where('name', 'user');
                })->count(),
                'active_users' => User::whereHas('roles', function ($q) {
                    $q->where('name', 'user');
                })->where('is_active', true)->count(),
                'new_users' => User::whereHas('roles', function ($q) {
                    $q->where('name', 'user');
                })->whereMonth('created_at', now()->month)->count(),
                'blocked_users' => User::whereHas('roles', function ($q) {
                    $q->where('name', 'user');
                })->where('is_active', false)->count(),
            ];

            return [
                'users' => $users,
                'stats' => $stats,
            ];
        });
    }

    public function show(int $userId)
    {
        return DB::transaction(function () use ($userId) {
            $user = User::withTrashed()->findOrFail($userId);
            $user->load([
                'image',
                'designs.images',
                'orders',
            ])->loadCount(['orders', 'designs']);

            return $user;
        });
    }

    public function updateStatus(array $data, User $user)
    {
        return DB::transaction(function () use ($data, $user) {

            $user->update([
                'is_active' => $data['is_active'],
            ]);

            return $user;
        });
    }
}
