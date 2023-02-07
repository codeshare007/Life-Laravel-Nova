<?php

namespace App\Http\Resources\v2_1;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="SolutionResource2.1",
 *     description="Individual solution response",
 *     type="object",
 *     title="Solution Resource V2.1",
 *)
 */
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
        $resource = [
            'type' => $this->solutionable->getApiModelName(),
            'solution_id' => $this->solutionable_id,
            'solution_type' => str_replace('App\\', '', $this->solutionable_type),
            'name' => $this->when($this->relationLoaded('solutionable'), $this->solutionable->name ?? null),
            'body' => $this->body,
            'views_count' => 0,
            'recent_views_count' => 0,
        ];

        if (showFullDetails($request)) {
           $resource['id'] = $this->id;
        }

        return array_filter($resource);
    }
}
