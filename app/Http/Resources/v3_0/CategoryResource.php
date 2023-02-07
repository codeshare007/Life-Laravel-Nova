<?php

namespace App\Http\Resources\v3_0;

use Illuminate\Support\Facades\Storage;

class CategoryResource extends BaseResource
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
            'body' => $this->body,
            'id' => $this->id,
            'color' => $this->color,
            'views_count' => 0,
            'recipes' => $this->uuidArray($this->whenLoaded('recipes')),
        ];

        return array_filter($resource);
    }
}
