<?php

namespace App\Http\Resources\v1_2;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class BlendResource extends JsonResource
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
            'resource_type' => 'Blend',
            'id' => $this->id,
            'name' => $this->name,
            'image_url' => $this->image_url ? Storage::url($this->image_url) : '',
            'color' => $this->color,
            'emotions' => [
                $this->emotion_1,
                $this->emotion_2,
                $this->emotion_3,
            ],
            'safety_information' => $this->when($this->relationLoaded('safetyInformation'), $this->safetyInformation->description ?? null),
            'fact' => $this->fact,
            'is_featured' => (int)$this->is_featured,
            'views_count' => 0,
            'comments_count' => 0,
            'ingredients' => BasicResource::collection($this->whenLoaded('ingredients')),
            'usages' => UsageResource::collection($this->whenLoaded('usages')),
        ];
    }
}
