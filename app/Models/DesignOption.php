<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class DesignOption extends Model
{
    //
    use HasTranslations;

    protected $translatable = ['name'];
    protected $fillable = [
        'name',
        'type',
        'is_active'
    ];

    public function itemsSelected(){
        return $this->hasMany(ItemOptionSelected::class);
    }
    
    public function designs()
    {
        return $this->belongsToMany(Design::class,'design_design_option');
    }

    public function itemsCart(){
        return $this->hasMany(ItemCart::class);
    }




}
