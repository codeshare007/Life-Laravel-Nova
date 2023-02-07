<?php

namespace App\Http\Resources\v3_0;

class RegionalNameResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request) : array
    {
        $resource = [
            'name' => $this->name,
            'id' => $this->region_id,
        ];

        return array_filter($resource);
    }
}
