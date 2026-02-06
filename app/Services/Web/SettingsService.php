<?php

namespace App\Services\Web;

use App\Models\NotificationPreference;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SettingsService
{
    public function getNotifyPermissions(User $user): Collection
    {
        return $user->getAllPermissions()
            ->filter(fn ($perm) => str_starts_with($perm->name, 'notify.'))
            ->values();
    }

    public function getPreferencesFor(User $user, Collection $permissions): Collection
    {
        return NotificationPreference::where('user_id', $user->id)
            ->whereIn('permission_id', $permissions->pluck('id'))
            ->get()
            ->keyBy('permission_id');
    }

    public function syncNotificationPreferences(User $user, Collection $permissions, Collection $enabledPermissions): void
    {
        $allowedIds = $permissions->pluck('id')->values();

        NotificationPreference::where('user_id', $user->id)
            ->whereNotIn('permission_id', $allowedIds)
            ->delete();

        foreach ($allowedIds as $permissionId) {
            $enabled = $enabledPermissions->contains($permissionId);

            NotificationPreference::updateOrCreate(
                ['user_id' => $user->id, 'permission_id' => $permissionId],
                ['enabled' => $enabled]
            );
        }
    }

    public function syncFromDashboard(User $user): void
    {
        $permissions = $this->getNotifyPermissions($user);
        $allowedIds = $permissions->pluck('id')->values();

        $disabledIds = NotificationPreference::where('user_id', $user->id)
            ->whereIn('permission_id', $allowedIds)
            ->where('enabled', false)
            ->pluck('permission_id')
            ->values();

        $enabledIds = $allowedIds->diff($disabledIds)->values();

        $this->syncNotificationPreferences($user, $permissions, $enabledIds);
    }

    public function updateNotifications(array $data)
    {
        return DB::transaction(function () use ($data) {
            $user = Auth::user();
            if (! $user) {
                abort(403);
            }

            $permissions = collect($data['permissions'])->unique()->values();
            $enabledPermissions = collect($validated['enabled_permissions'] ?? [])->unique()->values();
            $allowed = $this->getNotifyPermissions($user);
            $allowedIds = $allowed->pluck('id')->values();

            $filteredEnabled = $enabledPermissions->filter(fn ($id) => $allowedIds->contains($id))->values();

            $this->syncNotificationPreferences($user, $allowed, $filteredEnabled);
        });
    }
}
