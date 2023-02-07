<?php

namespace App\Http\Resources\v2_1;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="AilmentResource2.1",
 *     description="Individual ailment response",
 *     type="object",
 *     title="Ailment Resource V2.1",
 *     @OA\Property(
 *          property="type",
 *          type="string",
 *          description="Readable resource name"
 *     ),
 *     @OA\Property(
 *          property="uuid",
 *          type="string",
 *          description="Ailment UUID"
 *     ),
 *     @OA\Property(
 *          property="name",
 *          type="string",
 *          description="Ailment name"
 *     ),
 *     @OA\Property(
 *          property="color",
 *          type="string",
 *          description="Ailment color"
 *     ),
 *     @OA\Property(
 *          property="body",
 *          type="string",
 *          description="Ailment short description"
 *     ),
 *     @OA\Property(
 *          property="views_count",
 *          type="integer",
 *          description="Total view count on ailment"
 *     ),
 *     @OA\Property(
 *          property="comments_count",
 *          type="integer",
 *          description="Total number of comments for the ailment"
 *     ),
 *     @OA\Property(
 *          property="body_systems",
 *          type="array",
 *          description="A list of associated body systems",
 *          @OA\Items(type="string")
 *     ),
 *     @OA\Property(
 *          property="remedies",
 *          type="array",
 *          description="A list of associated remedies",
 *          @OA\Items(type="string")
 *     ),
 *     @OA\Property(
 *          property="solutions",
 *          type="array",
 *          description="A list of associated Solutions",
 *          @OA\Items(type="string")
 *     ),
 *     @OA\Property(
 *          property="secondary_solutions",
 *          type="array",
 *          description="A list of associated secondary Solutions",
 *          @OA\Items(type="string")
 *     ),
 *     @OA\Property(
 *          property="ailments",
 *          type="array",
 *          description="A list of associated Ailments",
 *          @OA\Items(type="string")
 *     ),
 *     @OA\Property(
 *          property="related_body_systems",
 *          type="array",
 *          description="A list of associated many to many body systems",
 *          @OA\Items(type="string")
 *     ),
 *     @OA\Property(
 *          property="symptoms",
 *          type="array",
 *          description="A list of associated Symptoms",
 *          @OA\Items(type="string")
 *     )
 * )
 */
class AilmentResource extends JsonResource
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
            'body_systems' => BasicResource::collection($this->whenLoaded('bodySystems')),
        ];

        if (showFullDetails($request)) {
            $resource = array_merge($resource, [
                'id' => $this->id,
                'color' => $this->color,
                'body' => $this->body,
                'views_count' => 0,
                'comments_count' => 0,
                'remedies' => AilmentRemedyResource::collection($this->whenLoaded('remedies')),
                'usages' => AilmentSolutionResource::collection($this->whenLoaded('solutions')),
                'secondary_solutions' => AilmentSecondarySolutionResource::collection($this->whenLoaded('secondarySolutions')),
                'ailments' => BasicResource::collection($this->whenLoaded('relatedAilments')),
                'related_body_systems' => BasicResource::collection($this->whenLoaded('relatedBodySystems')),
                'symptoms' => AilmentSymptomResource::collection($this->whenLoaded('symptoms')),
            ]);
        }

        return array_filter($resource);
    }
}
