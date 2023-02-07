<?php

namespace App\Http\Resources\v3_0;

class IngredientResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $name = $this->custom_name !== '' ?
            $this->custom_name :
            $this->name;
        $resource = [
            'type' => 'Ingredient',
            'uuid' => $this->uuid,
            'name' => $name,
            'quantity' => $this->quantity,
            'measure' => $this->measure,
            'ingredient_type' => ($this->resource->relationLoaded('ingredientable') && $this->ingredientable) ? $this->ingredientable->getApiModelName() ?? null : null,
            'element_uuid' => $this->resource->relationLoaded('ingredientable') ? $this->ingredientable->uuid ?? null : null,
        ];

        return array_filter($resource);
    }
}
