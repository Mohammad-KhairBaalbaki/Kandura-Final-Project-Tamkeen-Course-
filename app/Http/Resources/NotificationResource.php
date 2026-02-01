<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = is_array($this->data) ? $this->data : (array) $this->data;

        return [
            'id' => $this->id,

            'title' => $data['title'] ?? null,
            'body'  => $data['body'] ?? null,

            'read' => $this->read_at ,

            'created_at' => optional($this->created_at)?->toISOString(),
        ];
    }
}
