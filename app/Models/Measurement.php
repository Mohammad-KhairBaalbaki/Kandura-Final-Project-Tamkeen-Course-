<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Measurement extends Model
{
    //
    protected $fillable = [
        'size'
    ];

    public function design()
    {
        return $this->belongsToMany(Design::class, 'design_design_option');
    }
}
