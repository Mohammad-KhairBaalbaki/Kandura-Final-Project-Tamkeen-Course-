<?php

namespace App\Services\Web;

use App\Enums\StatusEnum;
use App\Models\Design;
use App\Models\Order;
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

            $latest_orders = Order::with(['user'])
                ->latest()
                ->take(5)
                ->get();

            return [
                'stats' => $stats,
                'latest_orders' => $latest_orders,
            ];
        });
    }
}

