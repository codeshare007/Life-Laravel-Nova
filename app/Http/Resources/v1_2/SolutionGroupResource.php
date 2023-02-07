<?php

namespace App\Http\Resources\v1_2;

use Illuminate\Http\Resources\Json\JsonResource;

class SolutionGroupResource extends JsonResource
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
            'useable_id' => $this->useable_id,
            'useable_type' => str_replace('App\\', '', $this->useable_type),
            'resource_type' => $this->getResourceName(),
            'id' => $this->id,
            'name' => $this->when($this->relationLoaded('useable'), $this->useable->name ?? null),
            'usage_description' => $this->pivot->usage_description,
            'views_count' => 0,
            'recent_views_count' => 0
        ];

        if ($this->pivot && $this->pivot->uses_application) {
            $usesApplication = collect(json_decode($this->pivot->uses_application, true))
                ->whereIn('active', true)
                ->sortBy('position')->map(function ($item, $key) {
                    return $item['name'];
                })->values();

            $resourceArr['uses_application'] = $usesApplication;
        }

        return $resourceArr;
    }

    public function getResourceName()
    {
        $className = str_replace('App\\', '', get_class($this->resource));

        return $className . 'Basic';
    }
}
