<?php

namespace App\Http\Resources\v3_0;

use Illuminate\Support\Facades\Storage;

class BodySystemResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $resource = [
            'type' => $this->getApiModelName(),
            'uuid' => $this->uuid,
            'name' => $this->name,
            'image_url' => $this->image_url ? Storage::url($this->image_url) : '',
            'id' => $this->id,
            'color' => $this->color,
            'body' => $this->short_description,
            'oils' => $this->uuidArray($this->whenLoaded('oils')),
            'blends' => $this->uuidArray($this->whenLoaded('blends')),
            'supplements' => $this->uuidArray($this->whenLoaded('supplements')),
            'remedies' => $this->uuidArray($this->whenLoaded('remedies')),
            'ailments' => $this->uuidArray($this->whenLoaded('ailments')),
            'symptoms' => $this->uuidArray($this->whenLoaded('symptoms')),
            'properties' => $this->uuidArray($this->whenLoaded('properties')),
        ];

        return array_filter($resource);
    }
}
