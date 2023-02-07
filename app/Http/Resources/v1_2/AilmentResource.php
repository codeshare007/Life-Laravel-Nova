<?php

namespace App\Http\Resources\v1_2;

use Illuminate\Http\Resources\Json\JsonResource;

class AilmentResource extends JsonResource
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
            'resource_type' => 'Ailment',
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
            'symptoms' => SymptomBasicResource::collection($this->whenLoaded('symptoms')), // This can be changed to a BasicResource once Symptoms and Ailments have been separated.
        ];
    }
}
