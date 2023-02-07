<?php

namespace App\Http\Resources\v1_2;

use Illuminate\Http\Resources\Json\JsonResource;

class AilmentSecondarySolutionResource extends JsonResource
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
            'resource_type' => 'AilmentSecondarySolution',
            'id' => $this->id,
            'useable_id' => $this->solutionable_id,
            'useable_type' => str_replace('App\\', '', $this->solutionable_type),
            'name' => $this->when($this->relationLoaded('solutionable'), $this->solutionable->name ?? null),
        ];
    }
}
