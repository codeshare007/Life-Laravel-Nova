<?php

namespace App\Http\Resources\v1_1;

use Illuminate\Http\Resources\Json\JsonResource;

class TopUseResource extends JsonResource
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
            'resource_type' => 'TopUse',
            'id' => $this->id,
            'ailment_id' => $this->pivot->ailment_id,
            'name' => $this->name,
            'description' => $this->description,
        ];
    }
}
