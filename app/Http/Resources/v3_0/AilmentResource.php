<?php

namespace App\Http\Resources\v3_0;

class AilmentResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        $resource = [
            'type' => $this->getApiModelName(),
            'uuid' => $this->uuid,
            'id' => $this->id,
            'name' => $this->name,
            'color' => $this->color,
            'body' => $this->body,
            'body_systems' => $this->uuidArray($this->whenLoaded('bodySystems')),
            'remedies' => $this->uuidArray($this->whenLoaded('remedies')),
            'solutions' => $this->uuidArray($this->whenLoaded('solutions')),
            'secondary_solutions' => $this->pluck($this->whenLoaded('secondarySolutions'), 'solutionable.uuid'),
            'ailments' => $this->uuidArray($this->whenLoaded('relatedAilments')),
            'related_body_systems' => $this->uuidArray($this->whenLoaded('relatedBodySystems')),
            'symptoms' => $this->uuidArray($this->whenLoaded('symptoms')),
        ];

        return array_filter($resource);
    }
}
