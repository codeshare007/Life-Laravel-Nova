<?php

namespace App\Http\Resources\v2_1;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="RecipeRelatedRecipeResource2.1",
 *     description="Individual solution response",
 *     type="object",
 *     title="Recipe Related Recipe Resource V2.1",
 *)
 */
class RecipeRelatedRecipeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $resource = [
            'type' => $this->getApiModelName(),
            'name' => $this->name,
            'uuid' => $this->uuid,
        ];

        if ($this->is_user_generated) {
            $resource['user_id'] = $this->user_id;
        }

        return array_filter($resource);
    }
}
