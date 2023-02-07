<?php

namespace App\Http\Resources\v1_1;

use Illuminate\Http\Resources\Json\JsonResource;

class RemedyIngredientResource extends JsonResource
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
            'resource_type' => 'RemedyIngredient',
            'id' => $this->id,
            'useable_id' => $this->ingredientable_id ?? 0,
            'useable_type' => str_replace('App\\', '', $this->ingredientable_type ?? ''),
            'name' => $this->name,
            'description' => $this->description,
        ];
    }
}
