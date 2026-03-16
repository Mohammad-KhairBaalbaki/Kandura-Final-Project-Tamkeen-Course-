<?php

namespace App\Services\Web;

use App\Enums\StatusEnum;
use App\Models\Design;
use App\Models\Order;
use App\Models\Review;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    public function index()
    {
        return DB::transaction(function () {
            $revenue = Order::where(function ($q) {
                $q->where('status', StatusEnum::DELIVERED)
                    ->orWhere('status', StatusEnum::CONFIRMED);
            });

            $stats = [
                'total_orders' => Order::count(),
                'total_users' => User::whereHas('roles', function ($q) {
                    $q->where('name', 'user');
                })->count(),
                'total_designs' => Design::count(),
                'active_users' => User::whereHas('roles', function ($q) {
                    $q->where('name', 'user');
                })->where('is_active', true)->count(),
                'total_discounts' => Order::sum('discount'),
                'total_revenue' => $revenue
                    ->selectRaw('SUM(subtotal - discount) as total')
                    ->value('total'),
                'active_designs' => Design::where('status', 'active')->count(),
                'total_deliverd' => Order::where('status', StatusEnum::DELIVERED)->count(),
            ];

            $latest_orders = Order::with(['user.image'])
                ->latest()
                ->take(5)
                ->get();

            $latest_reviews = Review::with(['user.image', 'order'])
                ->latest()
                ->take(5)
                ->get();

            $top_designs = Design::query()
                ->with(['images'])
                ->select(
                    'designs.id',
                    'designs.user_id',
                    'designs.name',
                    'designs.description',
                    'designs.price',
                    'designs.status',
                    'designs.created_at',
                    'designs.updated_at'
                )
                ->selectRaw('SUM(item_orders.quantity) as sales_count')
                ->join('item_orders', 'item_orders.design_id', '=', 'designs.id')
                ->join('orders', 'orders.id', '=', 'item_orders.order_id')
                ->whereIn('orders.status', [StatusEnum::DELIVERED, StatusEnum::CONFIRMED])
                ->groupBy(
                    'designs.id',
                    'designs.user_id',
                    'designs.name',
                    'designs.description',
                    'designs.price',
                    'designs.status',
                    'designs.created_at',
                    'designs.updated_at'
                )
                ->orderByDesc('sales_count')
                ->take(5)
                ->get();

            return [
                'stats' => $stats,
                'latest_orders' => $latest_orders,
                'latest_reviews' => $latest_reviews,
                'top_designs' => $top_designs,
            ];
        });
    }
}
