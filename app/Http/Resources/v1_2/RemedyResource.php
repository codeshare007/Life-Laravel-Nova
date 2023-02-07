<?php

namespace App\Http\Resources\v1_2;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class RemedyResource extends JsonResource
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
            'resource_type' => 'Remedy',
            'id' => $this->id,
            'name' => $this->name,
            'image_url' => $this->image_url ? Storage::url($this->image_url) : '',
            'color' => $this->color,
            'method' => $this->body,
            'views_count' => 0,
            'comments_count' => 0,
            'body_systems' => BasicResource::collection($this->whenLoaded('bodySystems')),
            'related_remedies' => BasicResource::collection($this->whenLoaded('relatedRemedies')),
            'ingredients' => RemedyIngredientResource::collection($this->whenLoaded('remedyIngredients')),
            'ailment' => BasicResource::make($this->whenLoaded('ailment')),
            'is_user_generated' => $this->is_user_generated,
            'user_id' => $this->user_id,
        ];
    }
}
