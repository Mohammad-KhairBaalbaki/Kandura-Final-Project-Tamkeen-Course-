<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Measurement extends Model
{
    //
    protected $fillable = [
        'size',
        'is_active'
    ];

    public function design()
    {
        return $this->belongsToMany(Design::class, 'design_design_option');
    }

    public function itemsCart(){
        return $this->hasMany(ItemCart::class);
    }
    
}
