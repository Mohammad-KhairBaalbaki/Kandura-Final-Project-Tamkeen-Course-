<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use App\Models\Perfume;
use App\Models\Review;

class DashboardController extends Controller
{
    public function index()
    {
        // Get statistics
        $stats = [
            // 'total_orders' => Order::count(),
            // 'total_revenue' => Order::where('status', 'delivered')->sum('total'),
            // 'total_users' => User::count(),
            // 'total_perfumes' => Perfume::count(),
            // 'active_perfumes' => Perfume::where('status', 'active')->count(),


            'total_orders' => 50,
            'total_revenue' => 10,
            'total_users' => 5,
            'total_perfumes' => 50,
            'active_perfumes' => 60,
        ];

        // Get latest 10 orders
        // $latest_orders = Order::with(['user', 'currency'])
        //     ->latest()
        //     ->take(10)
        //     ->get();

            $latest_orders = [];

        // Get latest 5 reviews
        // $latest_reviews = Review::with(['user', 'perfume'])
        //     ->latest()
        //     ->take(5)
        //     ->get();
            $latest_reviews =[];

        // Get top 5 selling perfumes
        // $top_perfumes = Perfume::select('perfumes.*')
        //     ->leftJoin('order_products', function ($join) {
        //         $join->on('perfumes.id', '=', 'order_products.model_id')
        //             ->where('order_products.model_type', 'perfume_user');
        //     })
        //     ->selectRaw('COUNT(order_products.id) as sales_count')
        //     ->groupBy('perfumes.id')
        //     ->orderByDesc('sales_count')
        //     ->take(5)
        //     ->get();

            $top_perfumes = [];

        return view('dashboard.home', compact(
            'stats',
            'latest_orders',
            'latest_reviews',
            'top_perfumes'
        ));
    }
}
