<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReviewResource extends JsonResource
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
            'order'=>$this->whenLoaded('order',fn()=>OrderResource::make($this->order)),
            'rating'=>$this->rating,
            'comment'=>$this->comment
        ];
    }
}
