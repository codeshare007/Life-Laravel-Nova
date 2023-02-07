<?php

namespace App\Http\Resources\v1_2;

use Illuminate\Http\Resources\Json\JsonResource;

class CollectableResource extends JsonResource
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
            'resource_type' => 'Collectable',
            'id' => $this->id,
            'collectable' => BasicResource::make($this->whenLoaded('collectable')),
        ];
    }
}
