<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Models\Order;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stripe\Checkout\Session;
use Stripe\Exception\ApiErrorException;
use Stripe\Stripe;

class StripeController extends Controller
{
    /**
     * @return View|Factory|Application
     */
    public function index(): View|Factory|Application
    {
        return view('stripe.index');
    }

    /**
     * @return RedirectResponse
     * @throws ApiErrorException
     */
    public function pay(Request $request): RedirectResponse
    {
        $user = Auth::user();
        Stripe::setApiKey(config('stripe.sk'));

        $session = Session::create([
            'customer_email' => $user->email,
            'payment_method_types' => ['card'],
            'line_items' => [
                [
                    'price_data' => [
                        'currency' => 'usd',
                        'product_data' => ['name' => 'Order Payment'],
                        'unit_amount' => $request->amount * 100,
                    ],
                    'quantity' => 1,
                ],
            ],
            'mode' => 'payment',
            'success_url' => route('success'),
            'cancel_url' => route('dashboard'),
            'metadata' => ['user_id' => $user->id, 'amount' => $request->amount * 100]
        ]);

        return redirect()->away($session->url);
    }

    public function result(Request $request, Order $order)
    {
        // Just show a "checking payment..." page
        return view('payment.result', [
            'order' => $order,
        ]);
    }

    public function status(Order $order)
    {
        // return what the webhook stored
        return response()->json([
            'status' => $order->status, // pending/paid/failed/expired
        ]);
    }

    public function successP(Order $order)
    {
        return view('payment.success', compact('order'));
    }

    public function failedP(Order $order)
    {
        return view('payment.failed', compact('order'));
    }

    /**
     * @return RedirectResponse
     * @throws ApiErrorException
     */

    /**
     * @return View|Factory|Application
     */
    public function success2()
    {
        return view('stripe.index');
    }
}


