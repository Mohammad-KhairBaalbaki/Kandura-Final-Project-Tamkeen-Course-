<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCouponRequest;
use App\Http\Requests\UpdateCouponRequest;
use App\Http\Requests\UpdateCouponStatusRequest;
use App\Models\Coupon;
use App\Services\Web\CouponService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CouponController extends Controller
{
    protected $couponService;

    public function __construct(CouponService $couponService)
    {
        $this->couponService = $couponService;
    }

    public function index(Request $request)
    {
        try {
            $coupons = $this->couponService->index($request);

            return view('coupons.index', compact('coupons'));
        } catch (\Exception $e) {
            Log::error($e);
            Log::error($e->getMessage());

            return back()->withErrors([
                'form' => __('coupons.process_failed'),
            ]);
        }
    }

    public function create()
    {
        try {
            return view('coupons.create');
        } catch (\Exception $e) {
            Log::error($e);
            Log::error($e->getMessage());

            return back()->withErrors([
                'form' => __('coupons.process_failed'),
            ]);
        }
    }

    public function store(StoreCouponRequest $request)
    {
        try {
            $this->couponService->store($request->validated());

            return redirect()
                ->route('coupons.index')
                ->with('success', __('coupons.created_success'));
        } catch (\Exception $e) {
            Log::error($e);
            Log::error($e->getMessage());

            return back()
                ->withInput()
                ->withErrors([
                    'form' => $e->getMessage(),
                ]);
        }
    }

    public function edit(Coupon $coupon)
    {
        try {
            $coupon = $this->couponService->edit($coupon);

            return view('coupons.edit', compact('coupon'));
        } catch (\Exception $e) {
            Log::error($e);
            Log::error($e->getMessage());

            return back()->withErrors([
                'form' => $e->getMessage(),
            ]);
        }
    }

    public function update(UpdateCouponRequest $request, Coupon $coupon)
    {
        try {
            $result = $this->couponService->update($request->validated(), $coupon);
            if (is_array($result) && ($result['error'] ?? null) === 'general_limit_too_low') {
                return back()
                    ->withInput()
                    ->withErrors([
                        'general_limit' => __('coupons.general_limit_too_low'),
                    ]);
            }

            return redirect()
                ->route('coupons.index')
                ->with('success', __('coupons.updated_success'));
        } catch (\Exception $e) {
            Log::error($e);
            Log::error($e->getMessage());

            return back()
                ->withInput()
                ->withErrors([
                    'form' => $e->getMessage(),
                ]);
        }
    }

    public function updateStatus(UpdateCouponStatusRequest $request, Coupon $coupon)
    {
        try {
            $this->couponService->updateStatus($request->validated(), $coupon);

            return back()->with('success', __('coupons.status_updated'));
        } catch (\Exception $e) {
            Log::error($e);
            Log::error($e->getMessage());

            return back()->withErrors([
                'is_active' => __('coupons.process_failed'),
            ]);
        }
    }
}
