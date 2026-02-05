<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class Address extends Model
{
    //
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'user_id',
        'city_id',
        'street',
        'latitude',
        'longitude',
        'details',
        'is_default'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
    public function city(){
        return $this->belongsTo(City::class);
    }

    public function orders(){
        return $this->hasMany(Order::class);
    }
}
