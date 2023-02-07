<?php

namespace App\Http\Resources\v3_0;

use Illuminate\Support\Facades\Storage;

class RecipeResource extends BaseResource
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
            'user_id' => $this->user_id,
            'image_url' => $this->image_url ? Storage::url($this->image_url) : '',
            'is_featured' => $this->is_featured,
            'body' => $this->body,
            'categories' => $this->uuidArray($this->whenLoaded('categories')),
            'related_recipes' => $this->uuidArray($this->whenLoaded('relatedRecipes')),
            'ingredients' => $this->uuidArray($this->whenLoaded('recipeIngredients')),
        ];

        return array_filter($resource);
    }
}
