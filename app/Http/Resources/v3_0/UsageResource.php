<?php

namespace App\Http\Resources\v3_0;

class UsageResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return array_filter([
            'type' => $this->getApiModelName(),
            'uuid' => $this->uuid,
            'name' => $this->name,
            'body' => $this->body,
            'uses_application' => $this->usesApplication(),
            'ailments' => $this->uuidArray($this->whenLoaded('ailments')),
        ]);
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
