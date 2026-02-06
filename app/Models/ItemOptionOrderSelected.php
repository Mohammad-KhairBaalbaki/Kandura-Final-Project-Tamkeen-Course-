<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemOptionOrderSelected extends Model
{
    //
    protected $fillable = [
        'item_order_id',
        'design_option_id',
    ];

    public function item()
    {
        return $this->belongsTo(ItemOrder::class, 'item_order_id');
    }

    public function designOption()
    {
        return $this->belongsTo(DesignOption::class, 'design_option_id');
    }
}
