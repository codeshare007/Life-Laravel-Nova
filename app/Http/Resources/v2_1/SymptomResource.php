<?php

namespace App\Http\Resources\v2_1;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

/**
 * @OA\Schema(
 *     schema="SymptomResource2.1",
 *     description="Individual symptom response",
 *     type="object",
 *     title="Symptom Resource V2.1",
 *     @OA\Property(
 *          property="type",
 *          type="string",
 *          description="Readable resource name"
 *     ),
 *     @OA\Property(
 *          property="uuid",
 *          type="string",
 *          description="Symptom UUID"
 *     ),
 *     @OA\Property(
 *          property="name",
 *          type="string",
 *          description="Symptom name"
 *     ),
 *     @OA\Property(
 *          property="image_url",
 *          type="string",
 *          description="Symptom image url"
 *     ),
 *     @OA\Property(
 *          property="color",
 *          type="string",
 *          description="Symptom color"
 *     ),
 *     @OA\Property(
 *          property="body",
 *          type="string",
 *          description="Symptom description"
 *     ),
 *     @OA\Property(
 *          property="views_count",
 *          type="integer",
 *          description="Total view count on symptom"
 *     ),
 *     @OA\Property(
 *          property="comments_count",
 *          type="integer",
 *          description="Total number of comments for the symptom"
 *     ),
 *     @OA\Property(
 *          property="body_systems",
 *          type="array",
 *          description="A list of associated body systems",
 *          @OA\Items(ref="#/components/schemas/SymptomBodySystemResource2.1")
 *     ),
 *     @OA\Property(
 *          property="remedies",
 *          type="array",
 *          description="A list of associated remedies",
 *          @OA\Items(ref="#/components/schemas/BasicResource2.1")
 *     ),
 *     @OA\Property(
 *          property="solutions",
 *          type="array",
 *          description="A list of associated solutions",
 *          @OA\Items(ref="#/components/schemas/AilmentSolutionResource2.1")
 *     ),
 *     @OA\Property(
 *          property="secondary_solutions",
 *          type="array",
 *          description="A list of associated secondary solutions",
 *          @OA\Items(ref="#/components/schemas/SymptomAilmentSecondarySolutionResource2.1")
 *     ),
 *     @OA\Property(
 *          property="ailments",
 *          type="array",
 *          description="A list of associated ailments",
 *          @OA\Items(ref="#/components/schemas/BasicResource2.1")
 *     ),
 *     @OA\Property(
 *          property="related_body_systems",
 *          type="array",
 *          description="A list of associated body systems",
 *          @OA\Items(ref="#/components/schemas/SymptomBodySystemResource2.1")
 *     ),
 *)
 */
class SymptomResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $resource = [
            'type' => $this->getApiModelName(),
            'uuid' => $this->uuid,
            'name' => $this->name,
            'body_systems' => SymptomBodySystemResource::collection($this->whenLoaded('bodySystems')),
        ];

        if (showFullDetails($request)) {
            $resource = array_merge($resource, [
                'id' => $this->id,
                'color' => $this->color,
                'body' => $this->body,
                'views_count' => 0,
                'comments_count' => 0,
                'remedies' => BasicResource::collection($this->whenLoaded('remedies')),
                'solutions' => AilmentSolutionResource::collection($this->whenLoaded('solutions')),
                'secondary_solutions' => SymptomAilmentSecondarySolutionResource::collection($this->whenLoaded('secondarySolutions')),
                'ailments' => BasicResource::collection($this->whenLoaded('relatedAilments')),
                'related_body_systems' => SymptomBodySystemResource::collection($this->whenLoaded('relatedBodySystems')),
            ]);
        }

        return array_filter($resource);
    }
}
