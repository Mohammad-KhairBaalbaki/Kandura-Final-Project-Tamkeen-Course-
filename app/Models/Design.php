<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class Design extends Model
{
    //
    use HasTranslations, SoftDeletes;

    protected $translatable = [
        'name',
        'description',
    ];
    protected $fillable = [
        'user_id',
        'name',
        'description',
        'price',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function images()
    {
        return $this->morphMany(Image::class, 'model');
    }

    public function designOptions()
    {
        return $this->belongsToMany(DesignOption::class,'design_design_option')->withTimestamps();
    }

    public function measurements()
    {
        return $this->belongsToMany(Measurement::class,'design_measurement')->withTimestamps();
    }

    public function itemsCart(){
        return $this->hasMany(ItemCart::class);
    }

    public function carts(){
        return $this->hasMany(Cart::class);
    }

    public function itemsOrder(){
        return $this->hasMany(ItemOrder::class);
    }

}
