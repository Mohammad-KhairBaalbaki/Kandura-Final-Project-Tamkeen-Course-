<?php

namespace App\Listeners;

use App\Events\DashboardNotificationRequested;
use App\Models\User;
use App\Notifications\Admin\AdminDashboardNotification;
use App\Services\FcmService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;

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

        // ✅ 2) خزّن بالـ DB (notifications table)
        Notification::send($recipients, new AdminDashboardNotification(
            $event->title,
            $event->body,
            $event->data
        )); // Laravel Notification facade :contentReference[oaicite:7]{index=7}

        // ✅ 3) ابعت Push عبر FCM
        $tokens = $recipients
            ->load('deviceTokens')
            ->flatMap(fn($u) => $u->deviceTokens->pluck('token'))
            ->filter()
            ->unique()
            ->values()
            ->all();

        // مهم: data values strings بالـ FCM
        $data = array_map(fn($v) => is_scalar($v) ? (string)$v : json_encode($v), $event->data);

        $this->fcm->sendToTokens($tokens, $event->title, $event->body, $data);
    }
}
