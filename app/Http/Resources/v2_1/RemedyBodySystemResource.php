<?php

namespace App\Http\Resources\v2_1;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="RemedyBodySystemResource2.1",
 *     description="Individual symptom basic response",
 *     type="object",
 *     title="Remedy BodySystem Resource V2.1",
 *)
 */
class RemedyBodySystemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $resourceArr = [
            'type' => $this->getApiModelName(),
            'name' => $this->name,
            'uuid' => $this->uuid
        ];

        return array_filter($resourceArr);
    }
}
