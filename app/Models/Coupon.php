<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    //
    protected $fillable = [
        'code',
        'validate_from',
        'validate_until',
        'is_percentage',
        'amount',
        'is_active',
        'order_limit_amount',
        'general_limit',
        'usages'

    ];



    public function orders(){
        return $this->hasMany(Order::class);
    }

    public function carts(){
        return $this->hasMany(Cart::class);
    }


}
