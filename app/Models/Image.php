<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    //

    protected $fillable = [
        'url'
    ];

    public function model()
    {
        return $this->morphTo();
    }
}
