<?php

namespace App\Http\Resources\v2_0;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

/**
 * @OA\Schema(
 *     schema="SupplementResource2.0",
 *     description="Individual supplement response",
 *     type="object",
 *     title="Supplement Resource V2.0",
 *     @OA\Property(
 *          property="resource_type",
 *          type="string",
 *          description="Readable resource name"
 *     ),
 *     @OA\Property(
 *          property="id",
 *          type="integer",
 *          description="Supplement name Id"
 *     ),
 *     @OA\Property(
 *          property="name",
 *          type="string",
 *          description="Supplement name"
 *     ),
 *     @OA\Property(
 *          property="image_url",
 *          type="string",
 *          description="Supplement name image url"
 *     ),
 *     @OA\Property(
 *          property="color",
 *          type="string",
 *          description="Supplement name color"
 *     ),
 *     @OA\Property(
 *          property="safety_information",
 *          type="string",
 *          description="Supplement safety information"
 *     ),
 *     @OA\Property(
 *          property="fact",
 *          type="string",
 *          description="Supplement fact"
 *     ),
 *     @OA\Property(
 *          property="research",
 *          type="string",
 *          description="Supplement research"
 *     ),
 *     @OA\Property(
 *          property="is_featured",
 *          type="boolean",
 *          description="Flag if supplement is featured"
 *     ),
 *     @OA\Property(
 *          property="views_count",
 *          type="integer",
 *          description="Total view count on supplement"
 *     ),
 *     @OA\Property(
 *          property="comments_count",
 *          type="integer",
 *          description="Total number of comments for the supplement"
 *     ),
 *     @OA\Property(
 *          property="top_uses",
 *          type="array",
 *          description="A list of the supplements top uses",
 *          @OA\Items(ref="#/components/schemas/TopUseResource2.0")
 *     ),
 *     @OA\Property(
 *          property="ingredients",
 *          type="array",
 *          description="A list of associated ingredients",
 *          @OA\Items(ref="#/components/schemas/SupplementIngredientResource2.0")
 *     ),
 *)
 */
class SupplementResource extends JsonResource
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
            'resource_type' => 'Supplement',
            'id' => $this->id,
            'name' => $this->name,
            'image_url' => $this->image_url ? Storage::url($this->image_url) : '',
            'color' => $this->color,
            'safety_information' => $this->when($this->relationLoaded('safetyInformation'), $this->safetyInformation->description ?? null),
            'fact' => $this->fact,
            'research' => $this->research,
            'is_featured' => (int)$this->is_featured,
            'views_count' => 0,
            'comments_count' => 0,
            'top_uses' => TopUseResource::collection($this->whenLoaded('supplementAilments')),
            'ingredients' => SupplementIngredientResource::collection($this->whenLoaded('supplementIngredients')),
        ];
    }
}
