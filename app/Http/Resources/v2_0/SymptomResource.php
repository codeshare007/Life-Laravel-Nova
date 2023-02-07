<?php

namespace App\Http\Resources\v2_0;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

/**
 * @OA\Schema(
 *     schema="SymptomResource2.0",
 *     description="Individual symptom response",
 *     type="object",
 *     title="Symptom Resource V2.0",
 *     @OA\Property(
 *          property="resource_type",
 *          type="string",
 *          description="Readable resource name"
 *     ),
 *     @OA\Property(
 *          property="id",
 *          type="integer",
 *          description="Symptom Id"
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
 *          property="short_description",
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
 *          @OA\Items(ref="#/components/schemas/BasicResource2.0")
 *     ),
 *     @OA\Property(
 *          property="remedies",
 *          type="array",
 *          description="A list of associated remedies",
 *          @OA\Items(ref="#/components/schemas/BasicResource2.0")
 *     ),
 *     @OA\Property(
 *          property="solutions",
 *          type="array",
 *          description="A list of associated solutions",
 *          @OA\Items(ref="#/components/schemas/AilmentSolutionResource2.0")
 *     ),
 *     @OA\Property(
 *          property="secondary_solutions",
 *          type="array",
 *          description="A list of associated secondary solutions",
 *          @OA\Items(ref="#/components/schemas/AilmentSecondarySolutionResource2.0")
 *     ),
 *     @OA\Property(
 *          property="related_ailments",
 *          type="array",
 *          description="A list of associated ailments",
 *          @OA\Items(ref="#/components/schemas/BasicResource2.0")
 *     ),
 *     @OA\Property(
 *          property="related_body_systems",
 *          type="array",
 *          description="A list of associated body systems",
 *          @OA\Items(ref="#/components/schemas/BasicResource2.0")
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
        return [
            'resource_type' => 'Symptom',
            'id' => $this->id,
            'name' => $this->name,
            'color' => $this->color,
            'short_description' => $this->short_description,
            'views_count' => 0,
            'comments_count' => 0,
            'body_systems' => BasicResource::collection($this->whenLoaded('bodySystems')),
            'remedies' => BasicResource::collection($this->whenLoaded('remedies')),
            'solutions' => AilmentSolutionResource::collection($this->whenLoaded('solutions')),
            'secondary_solutions' => AilmentSecondarySolutionResource::collection($this->whenLoaded('secondarySolutions')),
            'related_ailments' => BasicResource::collection($this->whenLoaded('relatedAilments')),
            'related_body_systems' => BasicResource::collection($this->whenLoaded('relatedBodySystems')),
        ];
    }
}
