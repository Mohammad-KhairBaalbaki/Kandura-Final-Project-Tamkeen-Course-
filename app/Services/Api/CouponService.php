<?php

namespace App\Services\Api;

use App\Enums\StatusEnum;
use App\Models\Cart;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class CouponService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    static public function isExpired(Coupon $coupon)
    {
        return DB::transaction(function () use ($coupon) {

            return !($coupon->validate_until > now() && $coupon->usages == $coupon->general_limit);
        });
    }


    static public function isUsed(Coupon $coupon, User $user)
    {
        return DB::transaction(function () use ($coupon, $user) {

            return $user->orders()
                ->where('coupon_id', $coupon->id)
                ->whereIn('status', [StatusEnum::CONFIRMED, StatusEnum::DELIVERED])
                ->exists();
        });
    }

    static public function checkOrderLimit(Cart $cart, Coupon $coupon)
    {
        return DB::transaction(function () use ($cart, $coupon) {

            return $cart->subtotal >= $coupon->order_limit_amount;
        });
    }
}

