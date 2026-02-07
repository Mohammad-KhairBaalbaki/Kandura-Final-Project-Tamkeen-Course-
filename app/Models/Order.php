<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    //
    protected $fillable = [
        'user_id',
        'num',
        'address_id',
        'status',
        'subtotal',
        'discount',
        'payment_id',
        'coupon_id',
    ];

    public function address()
    {
        return $this->belongsTo(Address::class);
    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    public function invoice()
    {
        return $this->hasOne(Invoice::class);
    }

    public function itemsOrder()
    {
        return $this->hasMany(ItemOrder::class);
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }

    public function review()
    {
        return $this->hasOne(Review::class);
    }
}
