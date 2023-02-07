<?php

namespace App\Http\Resources\v2_1;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

/**
 * @OA\Schema(
 *     schema="OilHowItsMadeResource2.1",
 *     description="Individual basic resource response",
 *     type="object",
 *     title="Oil HowItsMade Resource V2.1",
 *     @OA\Property(
 *          property="name",
 *          type="string",
 *          description="Resource name"
 *     )
 * )
 */
class OilHowItsMadeResource extends JsonResource
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

