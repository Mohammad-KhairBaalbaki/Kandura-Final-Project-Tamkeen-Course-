<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Image extends Model
{
    //

    protected $fillable = [
        'url',
    ];

    public function model()
    {
        return $this->morphTo();
    }

    public function getFullUrlAttribute()
    {
        return Storage::url($this->url);
    }
}
