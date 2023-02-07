<?php

namespace App\Http\Resources\v2_0;

use Illuminate\Support\Facades\Storage;
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
            'avatar_url' => $this->avatarUrl(),
        ];
    }
}
