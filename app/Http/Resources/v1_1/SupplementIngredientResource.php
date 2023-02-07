<?php

namespace App\Http\Resources\v1_1;

use Illuminate\Http\Resources\Json\JsonResource;

class SupplementIngredientResource extends JsonResource
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
            'resource_type' => 'SupplementIngredient',
            'id' => $this->id,
            'name' => $this->name,
            'useable_type' => str_replace('App\\', '', $this->ingredientable_type),
            'useable_id' => $this->ingredientable_id,
        ];
    }
}
