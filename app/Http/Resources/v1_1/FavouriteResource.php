<?php

namespace App\Http\Resources\v1_1;

use Illuminate\Http\Resources\Json\JsonResource;

class FavouriteResource extends JsonResource
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
            'resource_type' => 'Favourite',
            'id' => $this->id,
            'favouriteable_type' => str_replace('App\\', '', $this->favouriteable_type) ?? '',
            'favouriteable' => BasicResource::make($this->whenLoaded('favouriteable')),
        ];
    }
}
