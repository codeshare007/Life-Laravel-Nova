<?php

namespace App\Http\Resources\v1_2;

use Illuminate\Http\Resources\Json\JsonResource;

class AilmentSolutionResource extends JsonResource
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
            'resource_type' => 'AilmentSolution',
            'id' => $this->id,
            'useable_id' => $this->solutionable_id,
            'useable_type' => str_replace('App\\', '', $this->solutionable_type),
            'name' => $this->when($this->relationLoaded('solutionable'), $this->solutionable->name ?? null),
            'usage_description' => $this->uses_description,
            'uses_application' => $this->uses_application_list,
        ];
    }
}
