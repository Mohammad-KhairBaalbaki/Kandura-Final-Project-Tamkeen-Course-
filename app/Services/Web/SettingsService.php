<?php

namespace App\Services\Web;

use App\Models\NotificationPreference;
use App\Models\User;
use Illuminate\Support\Collection;

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
}
