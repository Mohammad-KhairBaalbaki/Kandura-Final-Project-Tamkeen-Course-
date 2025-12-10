<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemOptionSelected extends Model
{
    //
    protected $fillable = [
        'item_cart_id',
        'design_option_id',
    ];

    public function item(){
        return $this->belongsTo(ItemCart::class);
    }


    public function designOption(){
        return $this->belongsTo(DesignOption::class);
    }

}
