<?php

namespace App\Http\Resources\v3_0;

use Illuminate\Support\Facades\Storage;

class RemedyResource extends BaseResource
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
            'id' => $this->id,
            'name' => $this->name,
            'user_id' => $this->user_id,
            'image_url' => $this->image_url ? Storage::url($this->image_url) : '',
            'body' => $this->body,
            'ailments' => $this->whenLoaded('ailment') ? [$this->ailment->uuid ?? null] : null,
            'body_systems' => $this->uuidArray($this->whenLoaded('bodySystems')),
            'ingredients' => $this->uuidArray($this->whenLoaded('remedyIngredients')),
        ];

        return array_filter($resource);
    }
}
