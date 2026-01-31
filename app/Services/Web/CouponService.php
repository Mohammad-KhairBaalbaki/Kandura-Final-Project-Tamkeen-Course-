<?php

namespace App\Services\Web;

use App\Http\Requests\Web\StoreCouponRequest;
use App\Http\Requests\Web\UpdateCouponRequest;
use App\Http\Requests\Web\UpdateCouponStatusRequest;
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
                $query->where('code', 'like', '%' . $request->code . '%');
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

    public function store(StoreCouponRequest $request)
    {
        return DB::transaction(function () use ($request) {
            $validated = $request->validated();

            $validated['is_percentage'] = $request->boolean('is_percentage');
            $validated['is_active'] = $request->boolean('is_active');
            $validated['order_limit_amount'] = $validated['order_limit_amount'] ?? 0;

            return Coupon::create($validated);
        });
    }

    public function edit(Coupon $coupon)
    {
        return DB::transaction(function () use ($coupon) {
            return $coupon;
        });
    }

    public function update(UpdateCouponRequest $request, Coupon $coupon)
    {
        return DB::transaction(function () use ($request, $coupon) {
            $validated = $request->validated();

            if (($coupon->usages ?? 0) > $validated['general_limit']) {
                return [
                    'error' => 'general_limit_too_low',
                ];
            }

            $validated['is_percentage'] = $request->boolean('is_percentage');
            $validated['is_active'] = $request->boolean('is_active');
            $validated['order_limit_amount'] = $validated['order_limit_amount'] ?? 0;

            $coupon->update($validated);

            return $coupon;
        });
    }

    public function updateStatus(UpdateCouponStatusRequest $request, Coupon $coupon)
    {
        return DB::transaction(function () use ($request, $coupon) {
            $validated = $request->validated();

            $coupon->update([
                'is_active' => (bool) $validated['is_active'],
            ]);

            return $coupon;
        });
    }
}

