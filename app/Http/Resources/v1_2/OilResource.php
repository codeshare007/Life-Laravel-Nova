<?php

namespace App\Http\Resources\v1_2;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class OilResource extends JsonResource
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
            'resource_type' => 'Oil',
            'id' => $this->id,
            'name' => $this->name,
            'image_url' => $this->image_url ? Storage::url($this->image_url) : '',
            'color' => $this->color,
            'latin_name' => $this->latin_name,
            'emotions' => [
                $this->emotion_1,
                $this->emotion_2,
                $this->emotion_3,
            ],
            'emotion_from' => $this->emotion_from,
            'emotion_to' => $this->emotion_to,
            'safety_information' => $this->when($this->relationLoaded('safetyInformation'), $this->safetyInformation->description ?? null),
            'fact' => $this->fact,
            'is_featured' => (int)$this->is_featured,
            'research' => $this->research,
            'views_count' => 0,
            'comments_count' => 0,
            'related_recipes' => BasicResource::collection($this->whenLoaded('relatedRecipes')),
            'blends_with' => BasicResource::collection($this->whenLoaded('blendsWith')),
            'related_blends' => BasicResource::collection($this->whenLoaded('foundInBlends')),
            'found_in' => OilSolutionResource::collection($this->whenLoaded('foundIn')),
            'top_properties' => BasicResource::collection($this->whenLoaded('properties')),
            'main_constituents' => BasicResource::collection($this->whenLoaded('constituents')),
            'how_its_made' => BasicResource::collection($this->whenLoaded('sourcingMethods')),
            'top_uses' => UsageResource::collection($this->whenLoaded('usages')),
        ];
    }
}
