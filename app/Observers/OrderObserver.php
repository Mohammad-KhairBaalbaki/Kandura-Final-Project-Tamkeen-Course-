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
        $isDelivered = in_array($newStatus, ['delivered'], true);

        if ($isCancelled) {
            $user = $order->user;
            if ($user) {
                //send notification to user when order is cancelled
                $user->notify(new UserOrderNotification(
                    order: $order,
                    statusLabel: 'has been cancelled'
                ));

                //send notification to admin when order is cancelled
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
                //send notification to user when order is confirmed
                $user->notify(new UserOrderNotification(
                    order: $order,
                    statusLabel: 'has been confiremed'
                ));
            }
        }
        else if ($isDelivered) {
            $user = $order->user;
            if ($user) {
                //send notification to user when order is delivered
                $user->notify(new UserOrderNotification(
                    order: $order,
                    statusLabel: 'has been delivered'
                ));
            }
        }
        else
            {
                $user = $order->user;
            if ($user) {
                //send notification to user when order status is changed
                $user->notify(new UserOrderNotification(
                    order: $order,
                    statusLabel: 'has been updated from ' . $oldStatus . ' to ' . $newStatus
                ));
            }
            }

    }
}
