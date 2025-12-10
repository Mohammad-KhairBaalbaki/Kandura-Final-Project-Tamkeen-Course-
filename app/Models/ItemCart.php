<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemCart extends Model
{
    //
    protected $fillable = [
        'cart_id',
        'design_id',
        'quantity',
        'price',
        'measurement_id',
        'discount'
    ];


    /**
     * Get the item options that have been selected for this item cart
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function itemsSelected(){
        return $this->hasMany(ItemOptionSelected::class);
    }

    public function cart(){
        return $this->belongsTo(Cart::class);
    }

    public function design(){
        return $this->belongsTo(Design::class);
    }


    public function measurement(){
        return $this->belongsTo(Measurement::class);
    }
}
