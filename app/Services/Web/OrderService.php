<?php

namespace App\Services\Web;

use App\Enums\StatusEnum;
use App\Models\Order;
use App\Services\Global\InvoiceService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderService
{
    protected $invoiceService;

    public function __construct(InvoiceService $invoiceService)
    {
        $this->invoiceService = $invoiceService;
    }

    public function index(Request $request)
    {
        return DB::transaction(function () use ($request) {
            $query = Order::with(['user.image', 'payment']);

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('id', $search)->orWhere('num', $search)
                        ->orWhereHas('user', function ($userQuery) use ($search) {
                            $userQuery->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%")
                                ->orWhere('phone', 'like', "%{$search}%");
                        });
                });
            }

            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            if ($request->filled('payment_status')) {
                $query->whereHas('payment', function ($q) use ($request) {
                    $q->where('status', $request->payment_status);
                });
            }

            if ($request->filled('start_date')) {
                $query->whereDate('created_at', '>=', $request->start_date);
            }

            if ($request->filled('end_date')) {
                $query->whereDate('created_at', '<=', $request->end_date);
            }

            $orders = $query->latest()->paginate(15)->withQueryString();

            $stats = [
                'total_orders' => Order::count(),
                'pending_orders' => Order::where('status', StatusEnum::PENDING)->count(),
                'confirmed_orders' => Order::where('status', StatusEnum::CONFIRMED)->count(),
                'delivered_orders' => Order::where('status', StatusEnum::DELIVERED)->count(),
            ];

            return [
                'orders' => $orders,
                'stats' => $stats,
            ];
        });
    }

    public function show(Order $order)
    {
        return DB::transaction(function () use ($order) {
            $order->load([
                'itemsOrder.design',
                'itemsOrder.measurement',
                'itemsOrder.itemsSelected.designOption',
                'user.image',
                'coupon',
                'review',
                'address',
                'payment',
            ]);

            return $order;
        });
    }

    public function invoice(Order $order)
    {
        return DB::transaction(function () use ($order) {
            $num = $order->num ?? $order->id;
            $pdf = $this->invoiceService->orderPdf($order);
            return $pdf->stream('order-' . $num . '-invoice.pdf');
        });
    }

    public function failed(Order $order): RedirectResponse
    {
        return DB::transaction(function () use ($order) {
            return redirect()
                ->route('orders.show', $order->id)
                ->with('payment_failed', 'Payment failed. Please try again.');
        });
    }

    public function updateStatus(Request $request, Order $order): Order
    {
        return DB::transaction(function () use ($request, $order) {
            $validated = $request->validate([
                'status' => 'required|in:pending,confirmed,delivered,cancelled',
            ]);

            $order->update([
                'status' => $validated['status'],
            ]);

            return $order;
        });
    }
}
