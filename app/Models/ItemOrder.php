<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemOrder extends Model
{
    //

    protected $fillable = [
        'order_id',
        'design_id',
        'quantity',
        'price',
        'measurement_id',
        'discount',
    ];

    /**
     * Get the item options that have been selected for this item cart
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function itemsSelected()
    {
        return $this->hasMany(ItemOptionOrderSelected::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function design()
    {
        return $this->belongsTo(Design::class);
    }

    public function measurement()
    {
        return $this->belongsTo(Measurement::class);
    }

    public function getDesignOptionByType($type)
    {
        $selected = $this->itemsSelected()
            ->whereHas('designOption', function ($q) use ($type) {
                $q->where('type', $type);
            })
            ->with('designOption')
            ->first();

        return $selected?->designOption?->name;
    }

    public function getColorAttribute()
    {
        return $this->getDesignOptionByType('color');
    }

    public function getSleeveAttribute()
    {
        return $this->getDesignOptionByType('sleeve');
    }

    public function getDomeAttribute()
    {
        return $this->getDesignOptionByType('dome');
    }

    public function getFabricAttribute()
    {
        return $this->getDesignOptionByType('fabric');
    }
}
