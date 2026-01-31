<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=>$this->id,
            'user'=>$this->whenLoaded('user',fn()=>UserResource::make($this->user)),
            'subtotal'=>$this->subtotal,
            'discount'=>$this->discount,
            'itemsCart'=>$this->whenLoaded('itemsCart',fn()=>ItemCartResource::collection($this->itemsCart)),
            'coupon'=>$this->whenLoaded('coupon',fn()=>CouponResource::make($this->coupon))
        ];
    }
}
