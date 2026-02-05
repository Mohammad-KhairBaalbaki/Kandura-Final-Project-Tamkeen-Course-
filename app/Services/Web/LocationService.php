<?php

namespace App\Services\Web;

use App\Models\City;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LocationService
{
    public function cities(Request $request): array
    {
        return DB::transaction(function () use ($request) {
            $rows = City::query()
                ->select('cities.id', 'cities.name', DB::raw('COUNT(orders.id) as orders_count'))
                ->join('addresses', 'addresses.city_id', '=', 'cities.id')
                ->join('orders', 'orders.address_id', '=', 'addresses.id')
                ->groupBy('cities.id', 'cities.name')
                ->orderByDesc('orders_count')
                ->get();

            $totalOrders = $rows->sum('orders_count');
            $labels = [];
            $counts = [];
            $percentages = [];
            $colors = [];
            $palette = [
                '#6366F1', '#8B5CF6', '#EC4899', '#F97316', '#F59E0B',
                '#10B981', '#14B8A6', '#0EA5E9', '#3B82F6', '#A855F7',
            ];

            foreach ($rows as $index => $row) {
                $label = $row->getTranslation('name', app()->getLocale());
                $labels[] = $label;
                $counts[] = (int) $row->orders_count;
                $percentages[] = $totalOrders > 0
                    ? round(($row->orders_count / $totalOrders) * 100, 2)
                    : 0;
                $colors[] = $palette[$index % count($palette)];
            }

            return [
                'cityRows' => $rows,
                'labels' => $labels,
                'counts' => $counts,
                'percentages' => $percentages,
                'colors' => $colors,
                'totalOrders' => $totalOrders,
            ];
        });
    }
}
