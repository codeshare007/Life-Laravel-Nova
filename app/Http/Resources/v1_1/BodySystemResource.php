<?php

namespace App\Http\Resources\v1_1;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class BodySystemResource extends JsonResource
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
            'resource_type' => 'BodySystem',
            'id' => $this->id,
            'name' => $this->name,
            'image_url' => $this->image_url ? Storage::url($this->image_url) : '',
            'color' => $this->color,
            'description' => $this->short_description,
            'usage_tip' => $this->usage_tip,
            'views_count' => 0,
            'comments_count' => 0,
            'oils' => SolutionResource::collection($this->whenLoaded('oils')),
            'blends' => SolutionResource::collection($this->whenLoaded('blends')),
            'supplements' => SolutionResource::collection($this->whenLoaded('supplements')),
            'remedies' => BasicResource::collection($this->whenLoaded('remedies')->sortBy('name')),
            'ailments' => BasicResource::collection($this->whenLoaded('ailments')->sortBy('name')),
        ];
    }
}
