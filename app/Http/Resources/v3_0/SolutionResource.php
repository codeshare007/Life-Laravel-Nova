<?php

namespace App\Http\Resources\v3_0;

class SolutionResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $resource = [
            'type' => 'Solution',
            'uuid' => $this->uuid,
            'body' => $this->body,
            'uses_application' => $this->usesApplication(),
            'name' => $this->resource->relationLoaded('solutionable') ? $this->solutionable->name ?? null : null,
            'element_uuid' => $this->resource->relationLoaded('solutionable') ? $this->solutionable->uuid ?? null : null,
        ];

        return array_filter($resource);
    }

    protected function usesApplication()
    {
        if ($this->uses_application) {
            return collect(json_decode($this->uses_application, true))
                ->where('active', true)
                ->sortBy('position')
                ->pluck('name')
                ->values()
                ->toArray();
        }

        return null;
    }
}
