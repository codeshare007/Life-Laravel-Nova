<?php

namespace App\Http\Resources\v2_1;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

/**
 * @OA\Schema(
 *     schema="SupplementResource2.1",
 *     description="Individual supplement response",
 *     type="object",
 *     title="Supplement Resource V2.1",
 *     @OA\Property(
 *          property="type",
 *          type="string",
 *          description="Readable resource name"
 *     ),
 *     @OA\Property(
 *          property="uuid",
 *          type="string",
 *          description="Supplement UUID"
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
 *          property="usages",
 *          type="array",
 *          description="A list of the supplements top uses",
 *          @OA\Items(ref="#/components/schemas/SupplementUsageResource2.1")
 *     ),
 *     @OA\Property(
 *          property="ingredients",
 *          type="array",
 *          description="A list of associated ingredients",
 *          @OA\Items(ref="#/components/schemas/SupplementIngredientResource2.1")
 *     ),
 *)
 */
class SupplementResource extends JsonResource
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
            'color' => $this->color,
            'is_featured' => $this->is_featured,
        ];

        if (showFullDetails($request)) {
            $resource = array_merge($resource, [
                'id' => $this->id,
                'safety_information' => $this->whenLoaded('safetyInformation') ?
                    $this->safetyInformation->description :
                    null,
                'fact' => $this->fact,
                'research' => $this->research,
                'views_count' => 0,
                'comments_count' => 0,
                'usages' => SupplementUsageResource::collection($this->whenLoaded('supplementAilments'))->unique(),
                'ingredients' => SupplementIngredientResource::collection($this->whenLoaded('supplementIngredients')),
            ]);
        }

        return array_filter($resource);
    }
}
