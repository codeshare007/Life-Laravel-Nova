<?php

namespace App\Http\Resources\v2_0;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="TopUseResource2.0",
 *     description="Individual top user response",
 *     type="object",
 *     title="Top Use Resource V2.0",
 *     @OA\Property(
 *          property="resource_type",
 *          type="string",
 *          description="Readable resource name"
 *     ),
 *     @OA\Property(
 *          property="id",
 *          type="integer",
 *          description="Top use Id"
 *     ),
 *     @OA\Property(
 *          property="ailment_id",
 *          type="integer",
 *          description="Associated ailment id"
 *     ),
 *     @OA\Property(
 *          property="name",
 *          type="string",
 *          description="Top use name"
 *     ),
 *     @OA\Property(
 *          property="description",
 *          type="string",
 *          description="Top use description"
 *     ),
 *)
 */
class TopUseResource extends JsonResource
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
            'resource_type' => 'TopUse',
            'id' => $this->id,
            'ailment_id' => $this->pivot->ailment_id,
            'name' => $this->name,
            'description' => $this->description,
        ];
    }
}
