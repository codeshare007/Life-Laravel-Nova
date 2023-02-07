<?php

namespace App\Http\Resources\v1_2;

use Illuminate\Http\Resources\Json\JsonResource;

class BasicResource extends JsonResource
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
            'resource_type' => $this->getResourceName(),
            'id' => $this->id,
            'name' => $this->name,
            'views_count' => 0,
            'recent_views_count' => 0,
            'is_user_generated' => $this->is_user_generated ?? false,
        ];

        return $resourceArr;
    }

    public function getResourceName()
    {
        $className = str_replace('App\\', '', get_class($this->resource));

        return $className . 'Basic';
    }
}
