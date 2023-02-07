<?php

namespace App\Http\Resources\v2_0;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="AilmentSolutionResource2.0",
 *     description="Individual ailment solution response",
 *     type="object",
 *     title="Ailment Solution Resource V2.0",
 *     @OA\Property(
 *          property="resource_type",
 *          type="string",
 *          description="Readable resource name"
 *     ),
 *     @OA\Property(
 *          property="id",
 *          type="integer",
 *          description="Ailment solution Id"
 *     ),
 *     @OA\Property(
 *          property="useable_id",
 *          type="integer",
 *          description="Related solution Id"
 *     ),
 *     @OA\Property(
 *          property="useable_type",
 *          type="string",
 *          description="Solution type"
 *     ),
 *     @OA\Property(
 *          property="name",
 *          type="string",
 *          description="Solution name"
 *     ),
 *     @OA\Property(
 *          property="usage_description",
 *          type="string",
 *          description="Usage description"
 *     ),
 *     @OA\Property(
 *          property="uses_application",
 *          type="string",
 *          description="Uses application list"
 *     ),
 *)
 */
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
