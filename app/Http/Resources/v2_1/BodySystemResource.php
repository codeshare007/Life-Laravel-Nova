<?php

namespace App\Http\Resources\v2_1;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

/**
 * @OA\Schema(
 *     schema="BodySystemResource2.1",
 *     description="Individual Body System response",
 *     type="object",
 *     title="BodySystem Resource V2.1",
 *     @OA\Property(
 *          property="type",
 *          type="string",
 *          description="Readable resource name"
 *     ),
 *     @OA\Property(
 *          property="uuid",
 *          type="string",
 *          description="Body System UUID"
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
 *          property="body",
 *          type="string",
 *          description="Body System short description"
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
 *          @OA\Items(ref="#/components/schemas/BodySystemSolutionResource2.1")
 *     ),
 *     @OA\Property(
 *          property="blends",
 *          type="array",
 *          description="A list of associated blends",
 *          @OA\Items(ref="#/components/schemas/BodySystemSolutionResource2.1")
 *     ),
 *     @OA\Property(
 *          property="supplements",
 *          type="array",
 *          description="A list of associated supplements",
 *          @OA\Items(ref="#/components/schemas/BodySystemSolutionResource2.1")
 *     ),
 *     @OA\Property(
 *          property="remedies",
 *          type="array",
 *          description="A list of associated remedies",
 *          @OA\Items(ref="#/components/schemas/AilmentRemedyResource2.1")
 *     ),
 *     @OA\Property(
 *          property="ailments",
 *          type="array",
 *          description="A list of associated ailments",
 *          @OA\Items(ref="#/components/schemas/BodySystemAilmentResource2.1")
 *     ),
 *     @OA\Property(
 *          property="symptoms",
 *          type="array",
 *          description="A list of associated stymptoms",
 *          @OA\Items(ref="#/components/schemas/SymptomBasicResource2.1")
 *     ),
 *     @OA\Property(
 *          property="associated_properties",
 *          type="array",
 *          description="A list of associated properties",
 *          @OA\Items(ref="#/components/schemas/BodySystemPropertyResource2.1")
 *     )
 * )
 */
class BodySystemResource extends JsonResource
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
        ];

        if (showFullDetails($request)) {
            $resource = array_merge($resource, [
                'id' => $this->id,
                'color' => $this->color,
                'body' => $this->short_description,
                'views_count' => 0,
                'comments_count' => 0,
                'oils' => array_filter(
                    BodySystemSolutionResource::collection($this->whenLoaded('oils'))->toArray($request)
                ),
                'blends' => array_filter(
                    BodySystemSolutionResource::collection($this->whenLoaded('blends'))->toArray($request)
                ),
                'supplements' => array_filter(
                    BodySystemSolutionResource::collection($this->whenLoaded('supplements'))->toArray($request)
                ),
                'remedies' => AilmentRemedyResource::collection($this->whenLoaded('remedies')),
                'ailments' => BodySystemAilmentResource::collection($this->whenLoaded('ailments')),
                'symptoms' => SymptomBasicResource::collection($this->whenLoaded('symptoms')),
                'associated_properties' => BodySystemPropertyResource::collection($this->whenLoaded('properties')),
            ]);
        }

        return array_filter($resource);
    }
}
