<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\Web\SettingsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SettingsController extends Controller
{
    public function __construct(private SettingsService $settingsService) {}

    public function index()
    {
        try {
            $user = Auth::user();
            if (!$user) {
                abort(403);
            }

            $permissions = $this->settingsService->getNotifyPermissions($user);
            $prefs = $this->settingsService->getPreferencesFor($user, $permissions);

            return view('settings.index', [
                'permissions' => $permissions,
                'preferences' => $prefs,
            ]);
        } catch (\Exception $e) {
            Log::error($e);
            Log::error($e->getMessage());
            return $this->success(false, 'process failed try again later', 422);
        }
    }

    public function updateNotifications(Request $request)
    {
        try {
            $user = Auth::user();
            if (!$user) {
                abort(403);
            }

            $validated = $request->validate([
                'permissions' => ['required', 'array'],
                'permissions.*' => ['integer'],
                'enabled_permissions' => ['nullable', 'array'],
                'enabled_permissions.*' => ['integer'],
            ]);

            $permissions = collect($validated['permissions'])->unique()->values();
            $enabledPermissions = collect($validated['enabled_permissions'] ?? [])->unique()->values();
            $allowed = $this->settingsService->getNotifyPermissions($user);
            $allowedIds = $allowed->pluck('id')->values();

            $filteredEnabled = $enabledPermissions->filter(fn ($id) => $allowedIds->contains($id))->values();

            $this->settingsService->syncNotificationPreferences($user, $allowed, $filteredEnabled);

            return back()->with('success', __('settings.notifications_updated'));
        } catch (\Exception $e) {
            Log::error($e);
            Log::error($e->getMessage());
            return back()->withErrors([
                'notifications' => __('settings.notifications_update_failed'),
            ]);
        }
    }
}
