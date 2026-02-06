<?php

namespace App\Listeners;

use App\Events\DashboardNotificationRequested;
use App\Models\User;
use App\Notifications\Admin\AdminDashboardNotification;
use App\Services\FcmService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;
use Spatie\Permission\Models\Permission;

class SendDashboardNotification implements ShouldQueue
{
    public function __construct(private FcmService $fcm) {}

    public function handle(DashboardNotificationRequested $event): void
    {
        // ✅ 1) هات المستلمين: فقط يلي عندن permission
        $recipients = User::permission($event->permission)->get();

        if ($recipients->isEmpty()) {
            return;
        }

        $permissionId = Permission::where('name', $event->permission)->value('id');

        // ✅ 2) خزّن بالـ DB (notifications table)
        Notification::send($recipients, new AdminDashboardNotification(
            $event->title,
            $event->body,
            $event->data
        )); // Laravel Notification facade :contentReference[oaicite:7]{index=7}

        // ✅ 3) ابعت Push عبر FCM
        $recipients->load([
            'deviceTokens',
            'notificationPreferences' => function ($q) use ($permissionId) {
                if ($permissionId) {
                    $q->where('permission_id', $permissionId);
                }
            },
        ]);

        $tokens = $recipients
            ->filter(function ($user) use ($permissionId) {
                if (! $permissionId) {
                    return true;
                }
                $pref = $user->notificationPreferences->firstWhere('permission_id', $permissionId);

                return $pref ? (bool) $pref->enabled : true;
            })
            ->flatMap(fn ($u) => $u->deviceTokens->pluck('token'))
            ->filter()
            ->unique()
            ->values()
            ->all();

        // مهم: data values strings بالـ FCM
        $data = array_map(fn ($v) => is_scalar($v) ? (string) $v : json_encode($v), $event->data);

        $this->fcm->sendToTokens($tokens, $event->title, $event->body, $data);
    }
}
