<?php

namespace App\Http\Resources\v2_1;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

/**
 * @OA\Schema(
 *     schema="SupplementIngredientResource2.1",
 *     description="Individual supplement ingredient response",
 *     type="object",
 *     title="Supplement Ingredient Resource V2.1",
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
            $resource['body'] = $this->body ?? '';
        }

        return array_filter($resource);
    }
}
