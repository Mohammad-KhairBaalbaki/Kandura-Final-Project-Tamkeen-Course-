<?php

namespace App\Services\Api;

use App\Enums\PaymentMethodEnum;
use App\Enums\StatusEnum;
use App\Events\DashboardNotificationRequested;
use App\Models\Coupon;
use App\Models\ItemOptionOrderSelected;
use App\Models\ItemOrder;
use App\Models\Order;
use App\Models\Payment;
use App\Models\User;
use App\Notifications\User\UserInvoiceNotification;
use App\Notifications\User\UserOrderNotification;
use App\Notifications\User\UserWalletNotification;
use App\Services\Api\InvoiceService as ApiInvoiceService;
use App\Services\Global\InvoiceService;
use App\Services\Api\WalletService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Stripe\Checkout\Session;
use Stripe\Stripe;

class OrderService
{
    /**
     * Create a new class instance.
     */
    protected $walletService;
    protected $invoiceService;
    public function __construct(WalletService $walletService, InvoiceService $invoiceService)
    {
        $this->walletService = $walletService;
        $this->invoiceService = $invoiceService;
    }

    public function index()
    {
        return DB::transaction(function () {


            $user = User::find(Auth::id());
            if (!$user->orders || $user->orders->count() == 0) {
                return '2';
            }
            return $user->orders()->with('itemsOrder.design', 'itemsOrder.measurement', 'itemsOrder.itemsSelected.designOption', 'coupon', 'review')->get();
        });
    }

    static public function store(array $data)
    {
        return DB::transaction(function () use ($data) {

            $user = User::find(Auth::id());
            $cart = $user->cart;
            if (!$cart || $cart->itemsCart->count() == 0) {
                return '2';
            }
            if (isset($cart->coupon)) {
                $coupon = $cart->coupon;
                if (!CouponService::checkOrderLimit($cart, $cart->coupon)) {
                    return '3';
                }
                if (!$coupon->is_active) {
                    return '4';
                }
                if (CouponService::isUsed($coupon, Auth::user())) {
                    return '5';
                }
                if (CouponService::isExpired($coupon)) {
                    return '6';
                }

            }


            $payment = Payment::create([
                'user_id' => $user->id,
                'status' => StatusEnum::PENDING,
                'method' => $data['payment_method'],
                'amount' => $cart->subtotal - $cart->discount,

            ]);
            $payment->num = $payment->created_at->format('Ymd') . $payment->id;
            $payment->save();
            //////////////////////////

            //////////////////////////
            $order = Order::create([
                'user_id' => $user->id,
                'address_id' => $data['address_id'],
                'subtotal' => $cart->subtotal,
                'discount' => $cart->discount,
                'payment_id' => $payment->id,
                'coupon_id' => $cart->coupon_id
            ]);
            $order->num = $order->created_at->format('Ymd') . $order->id;
            $order->save();


            $invoice = (new ApiInvoiceService(new InvoiceService()))->store($order);

            //send notification to user when invoice is generated
            $user->notify(new UserInvoiceNotification(
                invoice: $invoice,
                event: 'generated',
            ));

            foreach ($cart->itemsCart as $item) {
                $orderItem = ItemOrder::create([
                    'order_id' => $order->id,
                    'design_id' => $item->design_id,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                    'measurement_id' => $item->measurement_id,
                    'discount' => $item->discount,
                ]);
                foreach ($item->itemsSelected as $option) {
                    $orderItemSelected = ItemOptionOrderSelected::create([
                        'item_order_id' => $orderItem->id,
                        'design_option_id' => $option->design_option_id,
                    ]);
                }

            }
            $cart->delete();
            $order->refresh();

            //send notification to admin when order is created
            event(new DashboardNotificationRequested(
                permission: 'notify.orders.created',
                title: 'New order Created',
                body: "Order #{$order->num} created by {$order->user->name}",
                data: [
                    'type' => 'admin.order',
                    'event' => 'created',
                    'order_id' => $order->id,
                    'url' => route('orders.show', $order->id),
                ]
            ));
            //send notification to user when order is created
            if ($order->user) {
                $user = $order->user;
                $user->notify(new UserOrderNotification(
                    order: $order,
                    statusLabel: 'Placed Successfully'
                ));
            }

            return $order->load('address', 'itemsOrder', 'itemsOrder.design', 'itemsOrder.measurement', 'itemsOrder.itemsSelected.designOption', 'coupon');

        });
    }

    public function pay(Order $order)
    {
        return DB::transaction(function () use ($order) {

            $coupon = $order->coupon;
            if (isset($coupon)) {
                if (CouponService::isExpired($coupon) || !$coupon->is_active) {
                    $order->coupon_id = null;

                    $order->discount = 0;
                    $order->save();
                    return '3';

                }
            }

            $payment = $order->payment;
            $payment->amount = $order->subtotal - $order->discount;
            $payment->save();
            if ($order->status != StatusEnum::PENDING) {
                return '1';
            }
            $user = Auth::user();
            if ($payment->method === PaymentMethodEnum::STRIPE) {
                Stripe::setApiKey(config('stripe.sk'));

                $session = Session::create([
                    'customer_email' => $user->email,
                    'payment_method_types' => ['card'],
                    'line_items' => [
                        [
                            'price_data' => [
                                'currency' => 'usd',
                                'product_data' => ['name' => 'Order Payment'],
                                'unit_amount' => $payment->amount * 100,
                            ],
                            'quantity' => 1,
                        ],
                    ],
                    'mode' => 'payment',
                    // ✅ Send the user to ONE page no matter what:
                    'success_url' => route('success_payment', $order) . '?session_id={CHECKOUT_SESSION_ID}',
                    'cancel_url' => route('failed_payment', $order) . '?canceled=1',

                    // ✅ Put identifiers somewhere reliable:
                    // Session metadata is fine for session events...
                    'metadata' => [
                        'order_id' => (int) $order->id,
                        'payment_id' => (int) $payment->id,
                        // 'user_id' => (int) $user->id,
                    ],

                    // ...but for payment_intent.* events, set it here too:
                    'payment_intent_data' => [
                        'metadata' => [
                            'order_id' => (int) $order->id,
                            'payment_id' => (int) $payment->id,
                            // 'user_id' => (int) $user->id,
                        ],
                    ],
                ]);
                return ($session->url);
            } elseif ($payment->method === PaymentMethodEnum::WALLET) {
                if ($this->walletService->checkWalletPay($user, $payment->amount)) {
                    $this->walletService->payWallet($user, $payment->amount);
                    return $this->successPayment($order);
                } else {
                    return '2';
                }
            } else {
                $order->method = StatusEnum::CONFIRMED;
                $this->useCoupon($order);
                $order->save;
                return $order;
            }
        });
    }

    public function successPayment($order)
    {
        return DB::transaction(function () use ($order) {

            $payment = $order->payment;
            $payment->status = StatusEnum::CONFIRMED;
            $order->status = StatusEnum::CONFIRMED;
            $payment->save();
            $order->save();
            $this->useCoupon($order);
            return $order->load('address', 'itemsOrder', 'itemsOrder.design', 'itemsOrder.measurement', 'itemsOrder.itemsSelected.designOption', 'coupon');
        });
    }

    public function useCoupon(Order $order)
    {

        return DB::transaction(function () use ($order) {
            if ($order->coupon_id) {
                $coupon = Coupon::whereKey($order->coupon_id)
                    ->lockForUpdate()
                    ->firstOrFail();

                $coupon->increment('usages');
            }
        });
    }

    public function markPaymentFailed(Order $order)
    {
        return DB::transaction(function () use ($order) {

            $payment = $order->payment;
            $payment->status = StatusEnum::FAILED;
            //send notifications to admin when payment failed
            event(new DashboardNotificationRequested(
                'notify.orders.issue',
                'Payment Failed',
                "Payment (#{$payment->num}) failed",
                [
                    'type' => 'super.admin',
                    'event' => 'created',
                    'payment_id' => $payment->id,

                ]
            ));
            //send notification to user when payment failed
            $user = $order->user;
            $user->notify(new UserWalletNotification(
                event: 'payment_failed',
                amount: $payment->amount,

            ));
            $payment->save();
            return $order;

        });
    }

    public function update(Order $order)
    {
        return DB::transaction(function () use ($order) {

            if ($order->status !== StatusEnum::PENDING) {
                return '2';
            }
            $order->status = StatusEnum::CANCELLED;
            $order->save();
            return $order;
        });
    }

}
