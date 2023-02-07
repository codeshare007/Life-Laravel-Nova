<?php

namespace App\Http\Resources\v1_2;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class CollectionResource extends JsonResource
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
            'resource_type' => 'Collection',
            'id' => $this->id,
            'name' => $this->name,
            'image_url' => $this->image_url ? Storage::url($this->image_url) : '',
            'description' => $this->description,
            'views_count' => 0,
            'comments_count' => 0,
            'user' => UserResource::make($this->whenLoaded('user')),
        ];
    }
}
