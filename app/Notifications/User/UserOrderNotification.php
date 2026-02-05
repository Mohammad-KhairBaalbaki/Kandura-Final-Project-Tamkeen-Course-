<?php

namespace App\Notifications\User;

use App\Models\Order;
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
        public Order $order,
        public ?string $statusLabel = null, 
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
        $label = $this->statusLabel ?? match ($this->order->status) {
            'confirmed'  => 'order confiremed',
            'delivered'  => 'order delivered',
            'cancelled'  => 'order cancelled',
            'pending'    => 'order pending',
            default      => 'order status updated',
        };

        return [
            'title' => 'order updated',
            'body'  => " your order #{$this->order->num}: {$label}",
            'data'  => [
                'type'     => 'order',
                'order_id' => $this->order->id,
                'status'   => $this->order->status,
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
