<?php

namespace App\Http\Resources\v2_1;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

/**
 * @OA\Schema(
 *     schema="BlendResource2.1",
 *     description="Individual blend resource response",
 *     type="object",
 *     title="Blend Resource V2.1",
 *     @OA\Property(
 *          property="type",
 *          type="string",
 *          description="Readable resource name"
 *     ),
 *     @OA\Property(
 *          property="uuid",
 *          type="string",
 *          description="Blend UUID"
 *     ),
 *     @OA\Property(
 *          property="name",
 *          type="string",
 *          description="Blend name"
 *     ),
 *     @OA\Property(
 *          property="image_url",
 *          type="string",
 *          description="Blend image url"
 *     ),
 *     @OA\Property(
 *          property="color",
 *          type="string",
 *          description="Blend color"
 *     ),
 *      @OA\Property(
 *          property="emotions",
 *          type="array",
 *          description="Top three emotions",
 *          @OA\Items(type="string")
 *     ),
 *     @OA\Property(
 *          property="fact",
 *          type="string",
 *          description="Blend fact"
 *     ),
 *     @OA\Property(
 *          property="is_featured",
 *          type="boolean",
 *          description="Flag if blend is featured"
 *     ),
 *     @OA\Property(
 *          property="views_count",
 *          type="integer",
 *          description="Total view count on blend"
 *     ),
 *     @OA\Property(
 *          property="comments_count",
 *          type="integer",
 *          description="Total number of comments for the blend"
 *     ),
 *     @OA\Property(
 *          property="safety_information",
 *          type="string",
 *          description="Safety information description",
 *     ),
 *     @OA\Property(
 *          property="ingredients",
 *          type="array",
 *          description="A list of associated ingredients",
 *          @OA\Items(ref="#/components/schemas/BlendIngredientResource2.1")
 *     ),
 *     @OA\Property(
 *          property="usages",
 *          type="array",
 *          description="A list of associated usages",
 *          @OA\Items(ref="#/components/schemas/UsageResource2.1")
 *     )
 *)
 */
class BlendResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $resource =  [
            'type' => $this->getApiModelName(),
            'uuid' => $this->uuid,
            'name' => $this->name,
            'image_url' => $this->image_url ? Storage::url($this->image_url) : '',
            'color' => $this->color,
            'is_featured' => $this->is_featured,
            'views_count' => 0,
        ];

        if (showFullDetails($request)) {
            $resource =array_merge($resource, [
                'id' => $this->id,
                'emotions' => [
                    $this->emotion_1,
                    $this->emotion_2,
                    $this->emotion_3,
                ],
                'fact' => $this->fact,
                'views_count' => 0,
                'comments_count' => 0,
                'safety_information' => $this->whenLoaded('safetyInformation') ?
                    $this->safetyInformation->description :
                    null,
                'ingredients' => BlendIngredientResource::collection($this->whenLoaded('ingredients')),
                'usages' => UsageResource::collection($this->whenLoaded('usages'))->unique(),
            ]);
        }

        return array_filter($resource);
    }
}
