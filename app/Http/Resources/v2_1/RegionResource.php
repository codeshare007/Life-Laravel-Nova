<?php

namespace App\Http\Resources\v2_1;

use App\Enums\Region;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="RegionResource2.1",
 *     description="Individual region response",
 *     type="object",
 *     title="Region Resource V2.1",
 *     @OA\Property(
 *          property="id",
 *          type="integer",
 *          description="Region name"
 *     ),
 *     @OA\Property(
 *          property="name",
 *          type="string",
 *          description="Region name"
 *     )
 *)
 */
class RegionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        /** @var Region $this */

        return [
            'id' => $this->value,
            'name' => $this->description,
        ];
    }
}
