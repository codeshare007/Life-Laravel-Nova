<?php

namespace App\Http\Resources\v2_0;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="RecipeIngredientResource2.0",
 *     description="Individual recipe ingredient response",
 *     type="object",
 *     title="Recipe Ingredient Resource V2.0",
 *     @OA\Property(
 *          property="resource_type",
 *          type="string",
 *          description="Readable resource name"
 *     ),
 *     @OA\Property(
 *          property="id",
 *          type="integer",
 *          description="Recipe ingredient Id"
 *     ),
 *     @OA\Property(
 *          property="name",
 *          type="string",
 *          description="Recipe name"
 *     ),
 *     @OA\Property(
 *          property="useable_id",
 *          type="integer",
 *          description="Ingredient use id"
 *     ),
 *     @OA\Property(
 *          property="useable_type",
 *          type="string",
 *          description="Ingredient use type"
 *     ),
 *)
 */
class RecipeIngredientResource extends JsonResource
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
            'resource_type' => 'RecipeIngredient',
            'id' => $this->id,
            'name' => $this->name,
            'useable_type' => str_replace('App\\', '', $this->ingredientable_type),
            'useable_id' => $this->ingredientable_id,
        ];
    }
}
