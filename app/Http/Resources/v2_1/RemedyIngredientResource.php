<?php

namespace App\Http\Resources\v2_1;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

/**
 * @OA\Schema(
 *     schema="RemedyIngredientResource2.1",
 *     description="Individual recipe ingredient response",
 *     type="object",
 *     title="Remedy Ingredient Resource V2.1",
 *     @OA\Property(
 *          property="name",
 *          type="string",
 *          description="Remedy Ingredient name"
 *     ),
 *     @OA\Property(
 *          property="uuid",
 *          type="string",
 *          description="Remedy Ingredient UUID"
 *     ),
 *     @OA\Property(
 *          property="image_url",
 *          type="string",
 *          description="Remdy ingredient image url"
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
        $name = $this->custom_name !== '' ?
            $this->custom_name :
            $this->name;
        $resource = [
            'uuid' => $this->ingredientable ? $this->ingredientable->uuid : $this->uuid,
            'name' => $name,
            'quantity' => $this->quantity,
            'measure' => $this->measure,
        ];

        if (showFullDetails($request)) {
            $resource['type'] = $this->ingredientable ?
                $this->ingredientable->getApiModelName() :
                null;
        }

        return array_filter($resource);
    }
}
