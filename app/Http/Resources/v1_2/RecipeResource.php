<?php

namespace App\Http\Resources\v1_2;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class RecipeResource extends JsonResource
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
            'resource_type' => 'Recipe',
            'id' => $this->id,
            'name' => $this->name,
            'image_url' => $this->image_url ? Storage::url($this->image_url) : '',
            'color' => $this->color,
            'is_featured' => (int)$this->is_featured,
            'body' => $this->body,
            'views_count' => 0,
            'comments_count' => 0,
            'categories' => BasicResource::collection($this->whenLoaded('categories')),
            'related_recipes' => BasicResource::collection($this->whenLoaded('relatedRecipes')),
            'ingredients' => RecipeIngredientResource::collection($this->whenLoaded('recipeIngredients')),
            'is_user_generated' => $this->is_user_generated,
            'user_id' => $this->user_id,
        ];
    }
}
