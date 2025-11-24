<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Design extends Model
{
    //
    use HasTranslations;

    protected $translatable = [
        'name',
        'description',
    ];
    protected $fillable = [
        'user_id',
        'name',
        'description',
        'price',
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
}
