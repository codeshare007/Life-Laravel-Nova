<?php

namespace App\Http\Resources\v2_1;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

/**
 * @OA\Schema(
 *     schema="PropertyResource2.1",
 *     description="Individual property response",
 *     type="object",
 *     title="Property Resource V2.1",
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
            'name' => $this->name,
        ];
    }
}
