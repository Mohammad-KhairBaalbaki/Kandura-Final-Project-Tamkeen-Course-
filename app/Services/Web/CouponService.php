<?php

namespace App\Services\Web;

use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class CouponService
{
    public function index(Request $request)
    {
        return DB::transaction(function () use ($request) {
            $query = Coupon::query();

            if ($request->filled('code')) {
                $query->where('code', 'like', '%'.$request->code.'%');
            }

            if ($request->filled('type')) {
                if ($request->type === 'percentage') {
                    $query->where('is_percentage', true);
                } elseif ($request->type === 'fixed') {
                    $query->where('is_percentage', false);
                }
            }

            if ($request->filled('is_active')) {
                $query->where('is_active', $request->is_active);
            }

            if ($request->filled('expired')) {
                $today = Carbon::today()->toDateString();
                if ($request->expired === '1') {
                    $query->where(function ($q) use ($today) {
                        $q->whereColumn('usages', '>=', 'general_limit')
                            ->orWhereDate('validate_until', '<', $today);
                    });
                } elseif ($request->expired === '0') {
                    $query->where(function ($q) use ($today) {
                        $q->whereColumn('usages', '<', 'general_limit')
                            ->whereDate('validate_until', '>=', $today);
                    });
                }
            }

            return $query->latest()->paginate(15)->withQueryString();
        });
    }

    public function store(array $data)
    {
        return DB::transaction(function () use ($data) {

            return Coupon::create($data);
        });
    }

    public function edit(Coupon $coupon)
    {
        return DB::transaction(function () use ($coupon) {
            return $coupon;
        });
    }

    public function update(array $data, Coupon $coupon)
    {
        return DB::transaction(function () use ($data, $coupon) {

            if (($coupon->usages ?? 0) > $data['general_limit']) {
                return [
                    'error' => 'general_limit_too_low',
                ];
            }
            $coupon->update($data);

            return $coupon;
        });
    }

    public function updateStatus(array $data, Coupon $coupon)
    {
        return DB::transaction(function () use ($data, $coupon) {

            $coupon->update($data);

            return $coupon;
        });
    }
}
