<?php

namespace App\Http\Resources\v2_1;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="TopUseResource2.1",
 *     description="Individual top user response",
 *     type="object",
 *     title="Top Use Resource V2.1",
 *     @OA\Property(
 *          property="type",
 *          type="string",
 *          description="Readable resource name"
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
 *          property="body",
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
            'name' => $this->name,
            'body' => $this->description,
        ];
    }
}
