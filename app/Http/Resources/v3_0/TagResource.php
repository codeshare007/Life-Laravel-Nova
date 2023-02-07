<?php

namespace App\Http\Resources\v3_0;

use App\Enums\TagType;

class TagResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $resource = [
            'type' => $this->tagType(),
            'uuid' => $this->uuid,
            'name' => $this->name,
            'oils' => $this->uuidArray($this->whenLoaded('oils')),
        ];

        return array_filter($resource);
    }

    protected function tagType()
    {
        if ($this->type === TagType::Property) {
            return 'Property';
        }

        if ($this->type === TagType::Constituent) {
            return 'Constituent';
        }

        throw new \Exception('Tried to build a TagResource using an invalid tag type.');
    }
}
