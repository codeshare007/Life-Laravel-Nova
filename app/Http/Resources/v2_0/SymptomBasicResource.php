<?php

namespace App\Http\Resources\v2_0;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="SymptomBasicResource2.0",
 *     description="Individual symptom basic response",
 *     type="object",
 *     title="Symptom Basic Resource V2.0",
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
            'resource_type' => 'SymptomBasic',
            'id' => $this->id,
            'name' => $this->name,
        ];

        return $resourceArr;
    }
}
