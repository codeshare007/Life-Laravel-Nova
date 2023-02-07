<?php

namespace App\Http\Resources\v2_0;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

/**
 * @OA\Schema(
 *     schema="PropertyResource2.0",
 *     description="Individual property response",
 *     type="object",
 *     title="Property Resource V2.0",
 *     @OA\Property(
 *          property="id",
 *          type="integer",
 *          description="Property Id"
 *     ),
 *     @OA\Property(
 *          property="name",
 *          type="string",
 *          description="Property name"
 *     ),
 *)
 */
class PropertyResource extends JsonResource
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
            'id' => $this->id,
            'name' => $this->name,
        ];
    }
}
