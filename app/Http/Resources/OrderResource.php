<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'num'=> $this->num,
            'user' => $this->whenLoaded('user', fn()=>UserResource::make($this->user)),
            'address' => $this->whenLoaded('address', fn()=>AddressResource::make($this->address)),
            'items_order' => $this->whenLoaded('itemsOrder', fn()=>ItemOrderResource::collection($this->itemsOrder)),
            'status' => $this->status,
            'subtotal' => $this->subtotal,
            'discount' => $this->discount,
            'coupon' => $this->whenLoaded('coupon', fn()=>CouponResource::make($this->coupon)),
            'review' => $this->whenLoaded('review', fn()=>ReviewResource::make($this->review)),
        ];
    }
}
