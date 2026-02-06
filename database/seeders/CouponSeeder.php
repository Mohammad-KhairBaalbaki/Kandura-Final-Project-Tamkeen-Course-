<?php

namespace Database\Seeders;

use App\Models\Coupon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class CouponSeeder extends Seeder
{
    public function run(): void
    {
        $today = Carbon::today();

        $coupons = [
            [
                'code' => 'WELCOME10',
                'validate_from' => $today->copy()->subDays(5),
                'validate_until' => $today->copy()->addDays(10),
                'is_percentage' => true,
                'amount' => 10,
                'is_active' => true,
                'order_limit_amount' => 0,
                'general_limit' => 100,
                'usages' => 5,
            ],
            [
                'code' => 'FIXED25',
                'validate_from' => $today->copy()->subDays(10),
                'validate_until' => $today->copy()->addDays(5),
                'is_percentage' => false,
                'amount' => 25,
                'is_active' => true,
                'order_limit_amount' => 100,
                'general_limit' => 50,
                'usages' => 1,
            ],
            [
                'code' => 'EXPIRED5',
                'validate_from' => $today->copy()->subDays(20),
                'validate_until' => $today->copy()->subDays(1),
                'is_percentage' => true,
                'amount' => 5,
                'is_active' => true,
                'order_limit_amount' => 0,
                'general_limit' => 10,
                'usages' => 10,
            ],
            [
                'code' => 'INACTIVE',
                'validate_from' => $today->copy()->subDays(2),
                'validate_until' => $today->copy()->addDays(20),
                'is_percentage' => false,
                'amount' => 15,
                'is_active' => false,
                'order_limit_amount' => 0,
                'general_limit' => 100,
                'usages' => 0,
            ],
        ];

        foreach ($coupons as $coupon) {
            Coupon::firstOrCreate(['code' => $coupon['code']], $coupon);
        }
    }
}
