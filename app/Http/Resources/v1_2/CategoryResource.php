<?php

namespace App\Http\Resources\v1_2;

use App\Enums\CategoryType;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class CategoryResource extends JsonResource
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
            'resource_type' => 'Category',
            'id' => $this->id,
            'category_type' => CategoryType::getDescription($this->type),
            'name' => $this->name,
            'image_url' => $this->image_url ? Storage::url($this->image_url) : '',
            'color' => $this->color,
            'short_description' => $this->short_description,
            'views_count' => 0,
            'recipes' => BasicResource::collection($this->whenLoaded('recipes')),
            'panels' => CategoryPanelResource::collection($this->whenLoaded('panels')),
            'top_tips' => CategoryTopTipResource::collection($this->whenLoaded('topTips')),
        ];
    }
}
