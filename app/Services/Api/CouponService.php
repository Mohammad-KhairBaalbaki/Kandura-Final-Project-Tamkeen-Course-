<?php

namespace App\Services\Api;

use App\Enums\StatusEnum;
use App\Models\Cart;
use App\Models\Coupon;
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

    public static function isExpired(Coupon $coupon)
    {
        return DB::transaction(function () use ($coupon) {

            return ! ($coupon->validate_until > now() && $coupon->usages == $coupon->general_limit);
        });
    }

    public static function isUsed(Coupon $coupon, User $user)
    {
        return DB::transaction(function () use ($coupon, $user) {

            return $user->orders()
                ->where('coupon_id', $coupon->id)
                ->whereIn('status', [StatusEnum::CONFIRMED, StatusEnum::DELIVERED])
                ->exists();
        });
    }

    public static function checkOrderLimit(Cart $cart, Coupon $coupon)
    {
        return DB::transaction(function () use ($cart, $coupon) {

            return $cart->subtotal >= $coupon->order_limit_amount;
        });
    }
}
