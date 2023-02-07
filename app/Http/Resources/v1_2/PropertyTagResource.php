<?php

namespace App\Http\Resources\v1_2;

use App\Enums\TagType;
use Illuminate\Http\Resources\Json\JsonResource;

class PropertyTagResource extends JsonResource
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
            'resource_type' => 'PropertyTag',
            'id' => $this->id,
            'tag_type' => TagType::getDescription($this->type),
            'name' => $this->name,
            'description' => $this->description,
            'oils' => BasicResource::collection($this->whenLoaded('oils')),
        ];
    }
}
