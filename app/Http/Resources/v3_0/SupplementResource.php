<?php

namespace App\Http\Resources\v3_0;

use Illuminate\Support\Facades\Storage;

class SupplementResource extends BaseResource
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
            'image_url' => $this->image_url ? Storage::url($this->image_url) : '',
            'color' => $this->color,
            'is_featured' => $this->is_featured,
            'fact' => $this->fact,
            'research' => $this->research,
            'safety_information' => $this->resource->relationLoaded('safetyInformation') ? $this->safetyInformation->description ?? null : null,
            'usages' => $this->pluck($this->whenLoaded('supplementAilments'), 'ailment.uuid'),
            'ingredients' => $this->uuidArray($this->whenLoaded('supplementIngredients')),
            'regional_names' => $this->whenLoaded('regionalNames')->isNotEmpty() ?
                RegionalNameResource::collection($this->whenLoaded('regionalNames')) :
                null,
        ];

        return array_filter($resource);
    }
}
