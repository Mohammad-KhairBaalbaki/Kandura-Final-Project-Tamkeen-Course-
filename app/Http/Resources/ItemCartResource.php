<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ItemCartResource extends JsonResource
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
            'cart' => $this->whenLoaded('cart', CartResource::make($this->cart)),
            'design' => $this->whenLoaded('design', DesignResource::make($this->design)),
            'options_selected' => $this->whenLoaded('itemsSelected', $this->itemsSelected),
            'quantity' => $this->quantity,
            'price' => $this->price,
            'measurement' => $this->whenLoaded('measurement', MeasurementResource::make($this->measurement)),
            'discount' => $this->discount
        ];
    }
}
