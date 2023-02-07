<?php

namespace App\Http\Resources\v2_0;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="CategoryTopTipResource2.0",
 *     description="Individual category top tip response",
 *     type="object",
 *     title="Category Top Tip Resource V2.0",
 *     @OA\Property(
 *          property="resource_type",
 *          type="string",
 *          description="Readable resource name"
 *     ),
 *      @OA\Property(
 *          property="id",
 *          type="integer",
 *          description="Category top tip Id"
 *     ),
 *     @OA\Property(
 *          property="description",
 *          type="string",
 *          description="Category top tip description"
 *     ),
 *)
 */
class CategoryTopTipResource extends JsonResource
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
            'resource_type' => 'CategoryTopTip',
            'id' => $this->id,
            'description' => $this->description,
        ];
    }
}
