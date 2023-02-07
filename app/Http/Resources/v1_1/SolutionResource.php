<?php

namespace App\Http\Resources\v1_1;

use Illuminate\Http\Resources\Json\JsonResource;

class SolutionResource extends JsonResource
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
            'resource_type' => 'Solution',
            'id' => $this->id,
            'solution_id' => $this->solutionable_id,
            'solution_type' => str_replace('App\\', '', $this->solutionable_type),
            'name' => $this->when($this->relationLoaded('solutionable'), $this->solutionable->name ?? null),
            'description' => $this->description,
            'views_count' => 0,
            'recent_views_count' => 0,
        ];
    }
}
