<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DesignResource extends JsonResource
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
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'status' => $this->status,
            'images' => $this->whenLoaded('images', fn () => ImageResource::collection($this->images)),
            'designOptions' => $this->whenLoaded('designOptions', fn () => DesignOptionResource::collection($this->designOptions)),
            'measurements' => $this->whenLoaded('measurements', fn () => MeasurementResource::collection($this->measurements)),
            'user' => $this->whenLoaded('user', fn () => UserResource::make($this->user)),
        ];
    }
}
