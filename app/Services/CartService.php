<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\Design;
use App\Models\ItemCart;
use App\Models\ItemOptionSelected;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

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
    }

    public function store(array $data)
    {
        $user = User::findOrFail(Auth::id());
        if (!isset($user->cart)) {
            $cart = Cart::create([
                'user_id' => $user->id,
            ]);
        } else {
            $cart = $user->cart;
        }
        $item = ItemCart::create([
            'cart_id' => $cart->id,
            'design_id' => $data['design_id'],
            'measurement_id' => $data['measurement_id'],
            'quantity' => $data['quantity'],
            'price' => Design::findOrFail($data['design_id'])->price,
        ]);

        foreach ($data['design_option_ids'] as $option) {
            ItemOptionSelected::create([
                'item_cart_id' => $item->id,
                'design_option_id' => $option
            ]);
        }

        return $item;

    }

    public function update()
    {
    }

    public function delete()
    {
    }
}
