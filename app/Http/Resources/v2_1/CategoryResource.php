<?php

namespace App\Http\Resources\v2_1;

use App\Enums\CategoryType;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

/**
 * @OA\Schema(
 *     schema="CategoryResource2.1",
 *     description="Individual category resource response",
 *     type="object",
 *     title="Category Resource V2.1",
 *     @OA\Property(
 *          property="type",
 *          type="string",
 *          description="Readable resource name"
 *     ),
 *     @OA\Property(
 *          property="uuid",
 *          type="string",
 *          description="Category UUID"
 *     ),
 *     @OA\Property(
 *          property="name",
 *          type="string",
 *          description="Category name"
 *     ),
 *     @OA\Property(
 *          property="color",
 *          type="string",
 *          description="Category color"
 *     ),
 *     @OA\Property(
 *          property="body",
 *          type="string",
 *          description="Category short description"
 *     ),
 *     @OA\Property(
 *          property="views_count",
 *          type="integer",
 *          description="Total view count on ailment"
 *     ),
 *     @OA\Property(
 *          property="recipes",
 *          type="array",
 *          description="A list of associated recipes",
 *          @OA\Items(ref="#/components/schemas/CategoryRecipeResource2.1")
 *     ),
 *)
 */
class CategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $resource = [
            'type' => $this->getApiModelName(),
            'uuid' => $this->uuid,
            'name' => $this->name,
            'image_url' => $this->image_url ? Storage::url($this->image_url) : '',
            'body' => $this->body,
        ];

        if (showFullDetails($request)) {
            $resource['id'] = $this->id;
            $resource = array_merge($resource, [
                'id' => $this->id,
                'color' => $this->color,
                'views_count' => 0,
                'recipes' => CategoryRecipeResource::collection($this->whenLoaded('recipes')),
            ]);
        }

        return array_filter($resource);
    }
}
