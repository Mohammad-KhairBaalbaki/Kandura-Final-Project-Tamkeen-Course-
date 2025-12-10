<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    //
    protected $fillable = [
        'user_id',
        'subtotal',
        'discount',
    ];

    public function design(){
        return $this->belongsTo(Design::class);
    }


    public function user(){
        return $this->belongsTo(User::class);
    }

    public function itemsCart(){
        return $this->hasMany(ItemCart::class);
    }

}
