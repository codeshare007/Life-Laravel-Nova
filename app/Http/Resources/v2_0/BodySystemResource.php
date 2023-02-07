<?php

namespace App\Http\Resources\v2_0;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

/**
 * @OA\Schema(
 *     schema="BodySystemResource2.0",
 *     description="Individual Body System response",
 *     type="object",
 *     title="Body System Resource 2.0",
 *     @OA\Property(
 *          property="resource_type",
 *          type="string",
 *          description="Readable resource name"
 *     ),
 *     @OA\Property(
 *          property="id",
 *          type="integer",
 *          description="Body System Id"
 *     ),
 *     @OA\Property(
 *          property="name",
 *          type="string",
 *          description="Body System name"
 *     ),
 *     @OA\Property(
 *          property="image_url",
 *          type="string",
 *          description="Body System image url"
 *     ),
 *     @OA\Property(
 *          property="color",
 *          type="string",
 *          description="Body System color"
 *     ),
 *     @OA\Property(
 *          property="description",
 *          type="string",
 *          description="Body System short description"
 *     ),
 *     @OA\Property(
 *          property="usage_tip",
 *          type="string",
 *          description="Usage tio for body system"
 *     ),
 *     @OA\Property(
 *          property="views_count",
 *          type="integer",
 *          description="Total view count on body system"
 *     ),
 *     @OA\Property(
 *          property="comments_count",
 *          type="integer",
 *          description="Total number of comments for the body system"
 *     ),
 *     @OA\Property(
 *          property="oils",
 *          type="array",
 *          description="A list of associated oils",
 *          @OA\Items(ref="#/components/schemas/SolutionResource2.0")
 *     ),
 *     @OA\Property(
 *          property="blends",
 *          type="array",
 *          description="A list of associated blends",
 *          @OA\Items(ref="#/components/schemas/SolutionResource2.0")
 *     ),
 *     @OA\Property(
 *          property="supplements",
 *          type="array",
 *          description="A list of associated supplements",
 *          @OA\Items(ref="#/components/schemas/SolutionResource2.0")
 *     ),
 *     @OA\Property(
 *          property="remedies",
 *          type="array",
 *          description="A list of associated remedies",
 *          @OA\Items(ref="#/components/schemas/BasicResource2.0")
 *     ),
 *     @OA\Property(
 *          property="ailments",
 *          type="array",
 *          description="A list of associated ailments",
 *          @OA\Items(ref="#/components/schemas/BasicResource2.0")
 *     ),
 *     @OA\Property(
 *          property="symptoms",
 *          type="array",
 *          description="A list of associated stymptoms",
 *          @OA\Items(ref="#/components/schemas/SymptomBasicResource2.0")
 *     ),
 *     @OA\Property(
 *          property="associated_properties",
 *          type="array",
 *          description="A list of associated properties",
 *          @OA\Items(ref="#/components/schemas/BasicResource2.0")
 *     )
 * )
 */
class BodySystemResource extends JsonResource
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
            'resource_type' => 'BodySystem',
            'id' => $this->id,
            'name' => $this->name,
            'image_url' => $this->image_url ? Storage::url($this->image_url) : '',
            'color' => $this->color,
            'description' => $this->short_description,
            'usage_tip' => $this->usage_tip,
            'views_count' => 0,
            'comments_count' => 0,
            'oils' => SolutionResource::collection($this->whenLoaded('oils')),
            'blends' => SolutionResource::collection($this->whenLoaded('blends')),
            'supplements' => SolutionResource::collection($this->whenLoaded('supplements')),
            'remedies' => BasicResource::collection($this->whenLoaded('remedies')),
            'ailments' => BasicResource::collection($this->whenLoaded('ailments')),
            'symptoms' => SymptomBasicResource::collection($this->whenLoaded('symptoms')),
            'associated_properties' => BasicResource::collection($this->whenLoaded('properties')),
        ];
    }
}
