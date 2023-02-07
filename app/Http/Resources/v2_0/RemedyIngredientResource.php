<?php

namespace App\Http\Resources\v2_0;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="RemedyIngredientResource2.0",
 *     description="Individual recipe ingredient response",
 *     type="object",
 *     title="Remedy Ingredient Resource V2.0",
 *     @OA\Property(
 *          property="resource_type",
 *          type="string",
 *          description="Readable resource name"
 *     ),
 *     @OA\Property(
 *          property="id",
 *          type="integer",
 *          description="Remedy ingredient Id"
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
 *     @OA\Property(
 *          property="name",
 *          type="string",
 *          description="Remedy name"
 *     ),
 *     @OA\Property(
 *          property="description",
 *          type="string",
 *          description="Remedy description"
 *     ),
 *)
 */
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
