<?php

namespace App\Http\Resources\v3_0;

use App\Recipe;
use App\RegionalName;
use App\Remedy;
use App\Ailment;

/**
 * @OA\Schema(
 *     schema="SearchResultResource3.0",
 *     type="object",
 *     title="Search Result Resource V3.0",
 *     @OA\Property(
 *          property="type",
 *          type="string"
 *     ),
 *     @OA\Property(
 *          property="uuid",
 *          type="string"
 *     ),
 *     @OA\Property(
 *          property="name",
 *          type="string"
 *     ),
 *     @OA\Property(
 *          property="user_id",
 *          type="string"
 *     ),
 *     @OA\Property(
 *          property="body_systems",
 *          type="array",
 *          @OA\Items()
 *     ),
 *     @OA\Property(
 *          property="categories",
 *          type="array",
 *          @OA\Items()
 *     ),
 *     @OA\Property(
 *          property="ailments",
 *          type="array",
 *          @OA\Items()
 *     ),
 *)
 */
class SearchResultResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request) : array
    {
        $resource = [
            'type' => $this->getApiModelName(),
            'uuid' => $this->uuid,
            'name' => $this->name,
            'user_id' => $this->user_id ?? null,
        ];

        if ($this->resource instanceof Ailment) {
            $resource['body_systems'] = $this->pluck($this->whenLoaded('bodySystems'), 'name');
        }

        if ($this->resource instanceof Recipe) {
            $resource['categories'] = $this->pluck($this->whenLoaded('categories'), 'name');
            $resource['user_id'] = $this->user_id;
        }

        if ($this->resource instanceof Remedy) {
            $resource['body_systems'] = $this->pluck($this->whenLoaded('bodySystems'), 'name');
            $resource['ailments'] = $this->whenLoaded('ailment') ? [$this->ailment->name ?? null] : null;
            $resource['user_id'] = $this->user_id;
        }

        return array_filter($resource);
    }
}
