<?php

namespace App\Http\Resources\v2_0;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="OilSolutionResource2.0",
 *     description="Individual oil solution response",
 *     type="object",
 *     title="Oil Solution Resource V2.0",
 *     @OA\Property(
 *          property="resource_type",
 *          type="string",
 *          description="Readable resource name"
 *     ),
 *     @OA\Property(
 *          property="id",
 *          type="integer",
 *          description="Oil solution Id"
 *     ),
 *     @OA\Property(
 *          property="useable_id",
 *          type="integer",
 *          description="Related oil Id"
 *     ),
 *     @OA\Property(
 *          property="useable_type",
 *          type="string",
 *          description="Solution type"
 *     ),
 *     @OA\Property(
 *          property="name",
 *          type="string",
 *          description="Oil solution name"
 *     ),
 *)
 */
class OilSolutionResource extends JsonResource
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
            'resource_type' => 'OilSolution',
            'id' => $this->id,
            'useable_id' => $this->useable_id ?? 0,
            'useable_type' => str_replace('App\\', '', $this->useable_type ?? ''),
            'name' => $this->name,
        ];
    }
}
