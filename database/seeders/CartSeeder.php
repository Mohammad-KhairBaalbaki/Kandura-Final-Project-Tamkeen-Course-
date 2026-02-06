<?php

namespace Database\Seeders;

use App\Enums\StatusEnum;
use App\Models\Cart;
use App\Models\Coupon;
use App\Models\Design;
use App\Models\DesignOption;
use App\Models\ItemCart;
use App\Models\ItemOptionSelected;
use App\Models\Measurement;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class CartSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::role('user')->get();
        
        $designs = Design::where('status', StatusEnum::ACTIVE)->get();
        if ($designs->isEmpty()) {
            $designs = Design::all();
        }

        $optionsByType = DesignOption::where('is_active', true)
            ->whereNull('deleted_at')
            ->get()
            ->groupBy('type');

        $measurements = Measurement::all();

        $activeCoupon = Coupon::where('is_active', true)
            ->whereDate('validate_until', '>=', Carbon::today())
            ->first();

        foreach ($users->take(3) as $index => $user) {
            $cart = Cart::firstOrCreate(
                ['user_id' => $user->id],
                ['subtotal' => 0, 'discount' => 0, 'coupon_id' => null]
            );

            if ($cart->itemsCart()->exists()) {
                continue;
            }

            $subtotal = 0;
            $selectedDesigns = $designs->shuffle()->take(2);

            foreach ($selectedDesigns as $design) {
                $measurementId = $design->measurements()->pluck('measurements.id')->first()
                    ?? $measurements->first()?->id;
                if (! $measurementId) {
                    continue;
                }

                $quantity = rand(1, 3);
                $price = $design->price;

                $item = ItemCart::create([
                    'cart_id' => $cart->id,
                    'design_id' => $design->id,
                    'measurement_id' => $measurementId,
                    'quantity' => $quantity,
                    'price' => $price,
                    'discount' => 0,
                ]);

                $subtotal += ($price * $quantity);

                foreach ($optionsByType as $group) {
                    $option = $group->first();
                    if ($option) {
                        ItemOptionSelected::create([
                            'item_cart_id' => $item->id,
                            'design_option_id' => $option->id,
                        ]);
                    }
                }
            }

            $discount = 0;
            $couponId = null;

            if ($index === 0 && $activeCoupon) {
                $discount = round($subtotal * 0.1, 2);
                $couponId = $activeCoupon->id;
            }

            $cart->update([
                'subtotal' => $subtotal,
                'discount' => $discount,
                'coupon_id' => $couponId,
            ]);
        }
    }
}
