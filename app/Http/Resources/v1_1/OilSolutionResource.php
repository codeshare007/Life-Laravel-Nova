<?php

namespace App\Http\Resources\v1_1;

use Illuminate\Http\Resources\Json\JsonResource;

class OilSolutionResource extends JsonResource
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
            'resource_type' => 'OilSolution',
            'id' => $this->id,
            'useable_id' => $this->useable_id ?? 0,
            'useable_type' => str_replace('App\\', '', $this->useable_type ?? ''),
            'name' => $this->name,
        ];
    }
}
