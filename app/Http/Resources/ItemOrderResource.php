<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ItemOrderResource extends JsonResource
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
            'design' => $this->whenLoaded('design', fn () => DesignResource::make($this->design)),
            'options_selected' => $this->whenLoaded('itemsSelected', fn () => ($this->itemsSelected)),
            'quantity' => $this->quantity,
            'price' => $this->price,
            'measurement' => $this->whenLoaded('measurement', fn () => MeasurementResource::make($this->measurement)),
            'discount' => $this->discount,
        ];
    }
}
