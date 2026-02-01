<?php

namespace App\Observers;

use App\Events\DashboardNotificationRequested;
use App\Models\Order;
use App\Notifications\User\UserOrderNotification;

class OrderObserver
{
    public function created(Order $order): void
    {

    }
    public function updated(Order $order): void
    {
        // تأكد أن الحالة تغيرت فعلياً
        if (!$order->wasChanged('status')) {
            return;
        }

        $newStatus = (string) $order->status;
        $oldStatus = (string) $order->getOriginal('status');

        $isCancelled = in_array($newStatus, ['cancelled'], true);
        $isConfirmed = in_array($newStatus, ['confirmed'], true);

        
        if ($isCancelled) {
            $user = $order->user;
            if ($user) {
                $user->notify(new UserOrderNotification(
                    order: $order,
                    statusLabel: 'has been cancelled'
                ));

                event(new DashboardNotificationRequested(
                    permission: 'notify.orders.cancelled',
                    title: 'Order cancelled',
                    body: "Order #{$order->num} was cancelled (from {$oldStatus} → {$newStatus})",
                    data: [
                        'type' => 'admin.order',
                        'event' => 'cancelled',
                        'order_id' => $order->id,
                        'old_status' => $oldStatus,
                        'new_status' => $newStatus,
                        'url' => route('orders.show', $order->id),
                    ]
                ));
            }
        } else if ($isConfirmed) {
            $user = $order->user;
            if ($user) {
                $user->notify(new UserOrderNotification(
                    order: $order,
                    statusLabel: 'has been confiremed'
                ));
            }
        }

    }
}
