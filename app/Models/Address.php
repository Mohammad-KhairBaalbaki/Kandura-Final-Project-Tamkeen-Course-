<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Address extends Model
{
    //
    use HasFactory;
    protected $fillable = [
        'user_id',
        'city_id',
        'street',
        'latitude',
        'longitude',
        'details',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
    public function city(){
        return $this->belongsTo(City::class);
    }
}
