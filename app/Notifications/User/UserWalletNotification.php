<?php

namespace App\Notifications\User;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class UserWalletNotification extends Notification
{
    use Queueable;

    public function __construct(
        public string $event,            // credited | debited | payment_failed
        public float $amount,
        public ?float $balance = null,
        public ?string $currency = 'USD',
        public ?int $orderNum = null
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase($notifiable): array
    {
        $currency = $this->currency ? " {$this->currency}" : '';
        $amountText = number_format($this->amount, 2).$currency;

        $label = match ($this->event) {
            'credited' => "wallet balance increased by {$amountText}",
            'debited' => "wallet balance deducted by {$amountText}",
            'payment_failed' => "payment failed ({$amountText})",
            default => "wallet updated ({$amountText})",
        };

        if ($this->orderNum) {
            $label .= " for order #{$this->orderNum}";
        }

        return [
            'title' => 'wallet',
            'body' => $label,
            'data' => [
                'type' => 'wallet',
                'event' => $this->event,
                'amount' => $this->amount,
                'currency' => $this->currency,
                'balance' => $this->balance,
                'order_id' => $this->orderNum,
            ],
        ];
    }
}
