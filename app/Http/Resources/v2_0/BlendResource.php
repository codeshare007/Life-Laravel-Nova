<?php

namespace App\Http\Resources\v2_0;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

/**
 * @OA\Schema(
 *     schema="BlendResource2.0",
 *     description="Individual blend resource response",
 *     type="object",
 *     title="Blend Resource 2.0",
 *     @OA\Property(
 *          property="resource_type",
 *          type="string",
 *          description="Readable resource name"
 *     ),
 *     @OA\Property(
 *          property="id",
 *          type="integer",
 *          description="Blend Id"
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
 *          @OA\Items(ref="#/components/schemas/BasicResource2.0")
 *     ),
 *     @OA\Property(
 *          property="usages",
 *          type="array",
 *          description="A list of associated usages",
 *          @OA\Items(ref="#/components/schemas/UsageResource2.0")
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
        return [
            'resource_type' => 'Blend',
            'id' => $this->id,
            'name' => $this->name,
            'image_url' => $this->image_url ? Storage::url($this->image_url) : '',
            'color' => $this->color,
            'emotions' => [
                $this->emotion_1,
                $this->emotion_2,
                $this->emotion_3,
            ],
            'fact' => $this->fact,
            'is_featured' => (int)$this->is_featured,
            'views_count' => 0,
            'comments_count' => 0,
            'safety_information' => $this->when($this->relationLoaded('safetyInformation'), $this->safetyInformation->description ?? null),
            'ingredients' => BasicResource::collection($this->whenLoaded('ingredients')),
            'usages' => UsageResource::collection($this->whenLoaded('usages')),
        ];
    }
}
