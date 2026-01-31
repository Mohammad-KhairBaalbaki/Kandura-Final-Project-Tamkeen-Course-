<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Models\Order;
use App\Models\Payment;
use App\Services\Api\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Stripe\Webhook;

class WebhookController extends Controller
{
    //
    public function handle(Request $request, OrderService $orderService)
    {
        try {
            $payload = $request->getContent();
            $sig = $request->header('Stripe-Signature');

            try {
                $event = Webhook::constructEvent(
                    $payload,
                    $sig,
                    env('STRIPE_WEBHOOK_SECRET')
                );
            } catch (\Throwable $e) {
                return response()->json(['error' => $e->getMessage()], 400);
            }

            $object = $event->data->object ?? null;

            // Try metadata from the event object
            $meta = $object->metadata ?? null;
            $orderId = $meta->order_id ?? null;
            $paymentId = $meta->payment_id ?? null;
            // Fallback: for some events, you might have to look deeper
            // (but with payment_intent_data metadata you usually won’t need this)

            $order = $orderId ? Order::find($orderId) : null;
            $payment = $paymentId ? Payment::find($paymentId) : null;
            if (!$order) {
                // Always 200 so Stripe doesn’t keep retrying forever
                return response()->json(['status' => 'ok', 'note' => 'order_not_found']);
            }
            Log::info($event->type);
            if ($payment) {
                // Log::info($payment->details);
                // $payment->details = json_encode($event->type) . ' ' . $payment->details;
                // $payment->save();
            }
            switch ($event->type) {

                // ✅ Checkout session finished
                case 'checkout.session.completed':
                    // payment_status: 'paid' | 'unpaid' | 'no_payment_required'
                    $paymentStatus = $object->payment_status ?? null;

                    if ($paymentStatus === 'paid') {
                        $orderService->successPayment($order);

                    } else {
                        // delayed method could be pending
                        // $orderService->markPaymentPending($order);
                    }
                    break;

                // ✅ Delayed methods
                case 'checkout.session.async_payment_succeeded':
                    $orderService->successPayment($order);
                    break;

                case 'checkout.session.async_payment_failed':
                    $orderService->markPaymentFailed($order);
                    break;

                case 'checkout.session.expired':
                    // $orderService->markPaymentExpired($order);
                    break;

                // ✅ If you want to support PI events too:
                case 'payment_intent.payment_failed':

                    $orderService->markPaymentFailed($order);
                    break;

                case 'payment_intent.succeeded':
                    $orderService->successPayment($order);
                    break;
                default:
                    return response()->json(['status' => 'ok', 'message' => 'Unknown event type: ' . $event->type]);

            }

            return response()->json(['status' => 'ok', 'message' => 'Webhook received']);
        } catch (\Exception $e) {
            Log::error($e);
            Log::error($e->getMessage());
            return $this->success(false, 'process failed try again later', 422);
        }
    }
}


