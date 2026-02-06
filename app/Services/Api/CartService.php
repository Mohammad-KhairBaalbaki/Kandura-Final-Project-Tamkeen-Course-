<?php

namespace App\Services\Api;

use App\Models\Cart;
use App\Models\Coupon;
use App\Models\Design;
use App\Models\ItemCart;
use App\Models\ItemOptionSelected;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function index()
    {
        return DB::transaction(function () {

            $cart = User::findOrFail(Auth::id())->cart;
            if (! $cart) {
                return '1';
            }

            $cart->load('itemsCart', 'coupon');

            // dd($cart->getRelations());
            return $cart;
        });
        // return Cart::where('user_id',Auth::id())->with('itemsCart')->get();
    }

    public function store(array $data)
    {
        return DB::transaction(function () use ($data) {

            $user = User::findOrFail(Auth::id());
            if (! isset($user->cart)) {
                $cart = Cart::create([
                    'user_id' => $user->id,
                ]);
            } else {
                $cart = $user->cart;
            }
            $designUnitPrice = Design::findOrFail($data['design_id'])->price;
            $item = ItemCart::create([
                'cart_id' => $cart->id,
                'design_id' => $data['design_id'],
                'measurement_id' => $data['measurement_id'],
                'quantity' => $data['quantity'],
                'price' => $designUnitPrice,
            ]);

            foreach ($data['design_option_ids'] as $option) {
                ItemOptionSelected::create([
                    'item_cart_id' => $item->id,
                    'design_option_id' => $option,
                ]);

            }
            $this->calculateTotal($cart);

            return $item;

        });
    }

    public function addCoupon(array $data)
    {
        return DB::transaction(function () use ($data) {

            $coupon = Coupon::where('code', $data['code'])->firstOrFail();

            if (! $coupon->is_active) {
                return '2';
            }
            if (CouponService::isUsed($coupon, Auth::user())) {
                return '3';
            }
            if (CouponService::isExpired($coupon)) {
                return '4';
            }
            $user = User::findOrFail(Auth::id());

            $cart = $user->cart;
            if (! isset($cart)) {
                $cart = Cart::create([
                    'user_id' => $user->id,
                ]);
            }

            if (! CouponService::checkOrderLimit($cart, $coupon)) {
                return '5';
            }
            $amount = 0;
            if ($coupon->is_percentage) {
                $amount = $cart->subtotal * $coupon->amount / 100;
            } else {
                $amount = $coupon->amount;
            }
            $cart->update([
                'coupon_id' => $coupon->id,
                'discount' => $amount,
            ]);
            $cart->save();

            return $cart->load('coupon');
        });
    }

    public function removeCoupon()
    {
        return DB::transaction(function () {
            $user = User::findOrFail(Auth::id());
            $cart = $user->cart;
            if (! $cart || ! $cart->coupon) {
                return '2';
            }
            $cart->update([
                'coupon_id' => null,
                'discount' => 0,
            ]);

            return $cart->load('coupon');
        });
    }

    public function update(array $data, ItemCart $item)
    {
        return DB::transaction(function () use ($data, $item) {

            $item->update($data);
            $cart = $item->cart;
            $this->calculateTotal($cart);

            return $item;
        });
    }

    public function delete(ItemCart $item)
    {
        return DB::transaction(function () use ($item) {

            $cart = $item->cart;
            $item->delete();
            $this->calculateTotal($cart);

            return true;
        });
    }

    public function calculateTotal(Cart $cart)
    {
        return DB::transaction(function () use ($cart) {

            $items = $cart->itemsCart;
            $cart->subtotal = 0;
            if ($items->count() > 0) {
                foreach ($items as $item) {
                    $cart->subtotal += ($item->price * $item->quantity);
                }
            } else {
                $cart->delete();

                return;
            }
            if (isset($cart->coupon)) {
                if ($cart->coupon->is_percentage) {
                    $cart->discount = $cart->subtotal * $cart->coupon->amount / 100;
                }
            }
            $cart->save();

        });
    }
}
