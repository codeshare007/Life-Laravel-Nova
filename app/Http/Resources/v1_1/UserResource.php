<?php

namespace App\Http\Resources\v1_1;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'resource_type' => 'User',
            'id' => $this->id,
            'name' => $this->name,
            'bio' => $this->bio,
            'avatar' => AvatarResource::make($this->whenLoaded('avatar')),
        ];
    }
}
