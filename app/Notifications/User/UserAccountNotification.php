<?php

namespace App\Notifications\User;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class UserAccountNotification extends Notification
{
    use Queueable;

    public function __construct(
        public string $action,          // activated | deactivated
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase($notifiable): array
    {
        $label = match ($this->action) {
            'activated'   => 'your account has been activated',
            'deactivated' => 'your account has been deactivated',
            default       => 'your account status has been updated',
        };

        $body = $label;


        return [
            'title' => 'account status',
            'body'  => $body,
            'data'  => [
                'type'   => 'account',
                'action' => $this->action,
            ],
        ];
    }
}
