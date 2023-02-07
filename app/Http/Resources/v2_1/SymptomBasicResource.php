<?php

namespace App\Http\Resources\v2_1;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="SymptomBasicResource2.1",
 *     description="Individual symptom basic response",
 *     type="object",
 *     title="Symptom Basic Resource V2.1",
 *)
 */
class SymptomBasicResource extends JsonResource
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
