<?php

namespace App\Notifications\User;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserOrderNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public int $orderNum,
        public string $status,
        public ?string $statusLabel = null, // نص لطيف للعرض (اختياري)
    ) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    // public function toMail(object $notifiable): MailMessage
    // {
    //     return (new MailMessage)
    //         ->line('The introduction to the notification.')
    //         ->action('Notification Action', url('/'))
    //         ->line('Thank you for using our application!');
    // }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */

    public function toDatabase($notifiable): array
    {
        $label = $this->statusLabel ?? match ($this->status) {
            'confirmed'  => 'order confiremed',
            'delivered'  => 'order delivered',
            'cancelled'  => 'order cancelled',
            default      => 'order status updated',
        };

        return [
            'title' => 'order updated',
            'body'  => " your order #{$this->orderNum}: {$label}",
            'data'  => [
                'type'     => 'order',
                'order_id' => $this->orderNum,
                'status'   => $this->status,
            ],
        ];
    }

    // public function toArray(object $notifiable): array
    // {
    //     return [
    //         //
    //     ];
    // }
}
