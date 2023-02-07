<?php

namespace App\Http\Resources\v1_2;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryPanelResource extends JsonResource
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
            'resource_type' => 'CategoryPanel',
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'background_image_url' => $this->background_image_url ? Storage::url($this->background_image_url) : '',
        ];
    }
}
