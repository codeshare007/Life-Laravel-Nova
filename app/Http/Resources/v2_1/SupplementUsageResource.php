<?php

namespace App\Http\Resources\v2_1;

use App\Ailment;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="SupplementUsageResource2.1",
 *     description="Individual usage response",
 *     type="object",
 *     title="Supplement Usage Resource V2.1",
 *     @OA\Property(
 *          property="type",
 *          type="string",
 *          description="Readable resource name V2.1"
 *     ),
 *     @OA\Property(
 *          property="uuid",
 *          type="string",
 *          description="Usage uuid"
 *     ),
 *     @OA\Property(
 *          property="name",
 *          type="string",
 *          description="Usage name"
 *     )
 *)
 */
class SupplementUsageResource extends JsonResource
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
            'uuid' => $this->ailment->uuid,
            'type' => $this->ailment->getApiModelName(),
            'name' => $this->ailment->name,
        ];
    }
}
