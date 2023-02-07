<?php

namespace App\Http\Resources\v3_0;

use Illuminate\Support\Facades\Storage;

class OilResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request) : array
    {
        $resource = [
            'type' => $this->getApiModelName(),
            'uuid' => $this->uuid,
            'name' => $this->name,
            'image_url' => $this->image_url ? Storage::url($this->image_url) : '',
            'color' => $this->color,
            'is_featured' => $this->is_featured,
            'id' => $this->id,
            'latin_name' => $this->latin_name,
            'emotions' => [
                $this->emotion_1,
                $this->emotion_2,
                $this->emotion_3,
            ],
            'emotion_from' => $this->emotion_from,
            'emotion_to' => $this->emotion_to,
            'fact' => $this->fact,
            'research' => $this->research,
            'safety_information' => $this->resource->relationLoaded('safetyInformation') ? $this->safetyInformation->description ?? null : null,
            'blends_with' => $this->uuidArray($this->whenLoaded('blendsWith')),
            'related_blends' => $this->uuidArray($this->whenLoaded('foundInBlends')),
            'found_in' => $this->pluck($this->whenLoaded('foundIn'), 'useable.uuid'),
            'properties' => $this->uuidArray($this->whenLoaded('properties')),
            'main_constituents' => $this->pluck($this->whenLoaded('constituents'), 'name'),
            'how_its_made' => $this->pluck($this->whenLoaded('sourcingMethods'), 'name'),
            'usages' => $this->uuidArray($this->whenLoaded('usages')),
            'regional_names' => $this->whenLoaded('regionalNames')->isNotEmpty() ?
                RegionalNameResource::collection($this->whenLoaded('regionalNames')) :
                null,
        ];

        return array_filter($resource);
    }
}
