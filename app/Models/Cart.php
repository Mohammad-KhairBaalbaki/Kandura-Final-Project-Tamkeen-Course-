<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    //
    protected $fillable = [
        'user_id',
        'subtotal',
        'coupon_id',
        'discount',
    ];



    public function coupon(){
        return $this->belongsTo(Coupon::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function itemsCart()
    {
        return $this->hasMany(ItemCart::class);
    }

}
