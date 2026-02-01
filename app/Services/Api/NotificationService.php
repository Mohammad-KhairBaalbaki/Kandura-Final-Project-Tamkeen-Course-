<?php

namespace App\Services\Api;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class NotificationService
{
    public function index(array $query)
    {
        return DB::transaction(function () use ($query) {
            $perPage = (int) ($query['per_page'] ?? 15);
            $perPage = max(1, min(50, $perPage));

            return Auth::user()
                ->notifications()
                ->latest()
                ->paginate($perPage)
            ;
        });
    }

    public function markRead(string $id)
    {
        return DB::transaction(function () use ($id) {
            $notification = Auth::user()
                ->notifications()
                ->where('id', $id)
                ->firstOrFail();

            if (is_null($notification->read_at)) {
                $notification->markAsRead();
                $notification->refresh();
            }

            return $notification;
        });
    }

    public function markAllRead()
    {
        return DB::transaction(function () {
            return Auth::user()
                ->unreadNotifications()
                ->update(['read_at' => now()]);
        });
    }

    public function unread(array $query)
    {
        return DB::transaction(function () use ($query) {
            $perPage = (int) ($query['per_page'] ?? 15);
            if ($perPage < 1) {
                $perPage = 15;
            } elseif ($perPage > 50) {
                $perPage = 50;
            }

            return Auth::user()
                ->unreadNotifications()
                ->latest()
                ->paginate($perPage)
                ->withQueryString();
        });
    }

    public function unreadCount()
    {
        return DB::transaction(function () {
            return Auth::user()
                ->unreadNotifications()
                ->count();
        });
    }
}
