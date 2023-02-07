<?php

namespace App\Http\Resources\v1_1;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class SupplementResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'resource_type' => 'Supplement',
            'id' => $this->id,
            'name' => $this->name,
            'image_url' => $this->image_url ? Storage::url($this->image_url) : '',
            'color' => $this->color,
            'safety_information' => $this->when($this->relationLoaded('safetyInformation'), $this->safetyInformation->description ?? null),
            'fact' => $this->fact,
            'research' => $this->research,
            'is_featured' => (int)$this->is_featured,
            'views_count' => 0,
            'comments_count' => 0,
            'top_uses' => TopUseResource::collection($this->whenLoaded('supplementAilments')),
            'ingredients' => SupplementIngredientResource::collection($this->whenLoaded('supplementIngredients')),
        ];
    }
}
