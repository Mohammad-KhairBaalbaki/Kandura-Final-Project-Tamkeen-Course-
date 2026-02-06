<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Invoice extends Model
{
    //
    protected $fillable = [
        'num',
        'order_id',
        'total',
        'pdf_url',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function getUrlAttribute()
    {
        $url = $this->pdf_url;
        if (! isset($url)) {
            return null;
        }

        return url(Storage::url($url));
    }
}
