<?php

namespace App\Http\Resources\v2_0;

use App\Enums\CategoryType;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

/**
 * @OA\Schema(
 *     schema="CategoryResource2.0",
 *     description="Individual category resource response",
 *     type="object",
 *     title="Category Resource V2.0",
 *     @OA\Property(
 *          property="resource_type",
 *          type="string",
 *          description="Readable resource name"
 *     ),
 *     @OA\Property(
 *          property="id",
 *          type="integer",
 *          description="Category Id"
 *     ),
 *     @OA\Property(
 *          property="category_type",
 *          type="string",
 *          description="Category type"
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
 *          property="short_description",
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
 *          @OA\Items(ref="#/components/schemas/BasicResource2.0")
 *     ),
 *     @OA\Property(
 *          property="panels",
 *          type="array",
 *          description="A list of associated panels",
 *          @OA\Items(ref="#/components/schemas/CategoryPanelResource2.0")
 *     ),
 *     @OA\Property(
 *          property="top_tips",
 *          type="array",
 *          description="A list of associated top tips",
 *          @OA\Items(ref="#/components/schemas/CategoryTopTipResource2.0")
 *     ),
 *)
 */
class CategoryResource extends JsonResource
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
            'resource_type' => 'Category',
            'id' => $this->id,
            'category_type' => CategoryType::getDescription($this->type),
            'name' => $this->name,
            'image_url' => $this->image_url ? Storage::url($this->image_url) : '',
            'color' => $this->color,
            'short_description' => $this->short_description,
            'views_count' => 0,
            'recipes' => BasicResource::collection($this->whenLoaded('recipes')),
            'panels' => CategoryPanelResource::collection($this->whenLoaded('panels')),
            'top_tips' => CategoryTopTipResource::collection($this->whenLoaded('topTips')),
        ];
    }
}
