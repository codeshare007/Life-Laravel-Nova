<?php

namespace App\Http\Resources\v2_1;

use App\Enums\UserGeneratedContentStatus;
use App\Recipe;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;
use Route;

class PublicUserGeneratedContentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        if ($this->type == 'Recipe') {
            return PublicUserCommunityRecipe::make($this);
        } else {
            return PublicUserCommunityRemedy::make($this);
        }
    }
}
