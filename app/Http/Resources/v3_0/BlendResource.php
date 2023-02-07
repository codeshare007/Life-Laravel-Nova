<?php

namespace App\Http\Resources\v3_0;

use Illuminate\Support\Facades\Storage;

class BlendResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request) : array
    {
        $resource =  [
            'type' => $this->getApiModelName(),
            'uuid' => $this->uuid,
            'name' => $this->name,
            'image_url' => $this->image_url ? Storage::url($this->image_url) : '',
            'color' => $this->color,
            'is_featured' => $this->is_featured,
            'id' => $this->id,
            'emotions' => [
                $this->emotion_1,
                $this->emotion_2,
                $this->emotion_3,
            ],
            'fact' => $this->fact,
            'safety_information' => $this->resource->relationLoaded('safetyInformation') ? $this->safetyInformation->description ?? null : null,
            'ingredients' => $this->uuidArray($this->whenLoaded('ingredients')),
            'usages' => $this->uuidArray($this->whenLoaded('usages')),
            'regional_names' => $this->whenLoaded('regionalNames')->isNotEmpty() ?
                RegionalNameResource::collection($this->whenLoaded('regionalNames')) :
                null,
        ];

        return array_filter($resource);
    }
}
