<?php

namespace App\Observers;

use App\Events\DashboardNotificationRequested;
use App\Models\Order;
use App\Notifications\User\UserOrderNotification;

class OrderObserver
{
    public function created(Order $order): void
    {
        //send notification to admin when order is created
        event(new DashboardNotificationRequested(
            permission: 'notify.orders.created',
            title: 'New order Created',
            body: "Order #{$order->num} created by {$order->user->name}",
            data: [
                'type' => 'admin.order',
                'event' => 'created',
                'order_id' => $order->id,
            ]
        ));
    }
    public function updated(Order $order): void
    {
        // تأكد أن الحالة تغيرت فعلياً
        if (!$order->wasChanged('status')) {
            return;
        }

        $newStatus = (string) $order->status;
        $oldStatus = (string) $order->getOriginal('status');

        // اعتبرها cancelled بأي تهجئة
        $isCancelled = in_array($newStatus, ['cancelled', 'canceled'], true);

        if (!$isCancelled) {
            return;
        }

        // ✅ 1) إشعار المستخدم (Database)
        // (يفترض عندك علاقة $order->user)
        if ($order->relationLoaded('user') || method_exists($order, 'user')) {
            $user = $order->user; // عدّل إذا عندك اسم علاقة مختلف
            if ($user) {
                $user->notify(new UserOrderNotification(
                    orderNum: $order->id,
                    status: $newStatus,
                    statusLabel: 'order cancelled'
                ));
            }
        }

        // ✅ 2) إشعار الأدمنز يلي عندهم permission الخاصة بالإلغاء (DB + Push عبر listener)
        event(new DashboardNotificationRequested(
            permission: 'notify.orders.cancelled',
            title: 'Order cancelled',
            body: "Order #{$order->id} was cancelled (from {$oldStatus} → {$newStatus})",
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
}
