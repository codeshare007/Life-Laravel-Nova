<?php

namespace App\Http\Resources\v1_1;

use Illuminate\Http\Resources\Json\JsonResource;

class UsageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        if ($this->uses_application) {
            $usesApplication = collect(json_decode($this->uses_application, true))
                ->whereIn('active', true)
                ->sortBy('position')->map(function ($item, $key) {
                    return $item['name'];
                })->values();
        }

        return [
            'resource_type' => 'Usage',
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'uses_application' => $usesApplication ?? null,
            'views_count' => 0,
            'recent_views_count' => 0,
            'ailments' => BasicResource::collection($this->whenLoaded('ailments')),
        ];
    }
}
