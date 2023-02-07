<?php

namespace App\Http\Resources\v2_1;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

/**
 * @OA\Schema(
 *     schema="OilMainConstituentsResource2.1",
 *     description="Individual basic resource response",
 *     type="object",
 *     title="Oil MainConstituents ResourceV2.1",
 *     @OA\Property(
 *          property="name",
 *          type="string",
 *          description="Resource name"
 *     )
 * )
 */
class OilMainConstituentsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return  [
            'name' => $this->name,
        ];
    }
}

