<?php

namespace App\Http\Resources\v1_2;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Enums\FavouriteableModels;
use App\Enums\AilmentType;

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
        // This condition check can be removed when symptoms become a separate model.
        if ($this->favouriteable_type === FavouriteableModels::Ailment && $this->favouriteable->ailment_type === AilmentType::Symptom) {
            return [
                'resource_type' => 'Favourite',
                'id' => $this->id,
                'favouriteable_type' => 'Symptom',
                'favouriteable' => SymptomBasicResource::make($this->whenLoaded('favouriteable')),
            ];
        }

        return [
            'resource_type' => 'Favourite',
            'id' => $this->id,
            'favouriteable_type' => str_replace('App\\', '', $this->favouriteable_type) ?? '',
            'favouriteable' => BasicResource::make($this->whenLoaded('favouriteable')),
        ];
    }
}
