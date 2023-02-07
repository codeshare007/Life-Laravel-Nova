<?php

namespace App\Http\Resources\v2_0;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="SupplementIngredientResource2.0",
 *     description="Individual supplement ingredient response",
 *     type="object",
 *     title="Supplement Ingredient Resource V2.0",
 *)
 */
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
