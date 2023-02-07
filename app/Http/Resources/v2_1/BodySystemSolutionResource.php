<?php

namespace App\Http\Resources\v2_1;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="BodySystemSolutionResource2.1",
 *     description="Individual solution response",
 *     type="object",
 *     title="BodySystem Solution Resource V2.1",
 *)
 */
class BodySystemSolutionResource extends JsonResource
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
            'type' => $this->solutionable ?
                $this->solutionable->getApiModelName() :
                str_replace('App\\', '', $this->solutionable_type),
            'name' => $this->when($this->relationLoaded('solutionable'), $this->solutionable->name ?? $this->name),
            'uuid' => $this->solutionable ? $this->solutionable->uuid : $this->uuid,
        ];

        return array_filter($resource);
    }
}
