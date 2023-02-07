<?php

namespace App\Http\Resources\v1_2;

use Illuminate\Http\Resources\Json\JsonResource;

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
