<?php

namespace App\Http\Resources\v2_1;

use App\Enums\UserGeneratedContentStatus;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $content = $this->content->where('status', UserGeneratedContentStatus::Accepted);
        return array_filter([
            'resource_type' => 'User',
            'id' => $this->id,
            'name' => $this->name,
            'bio' => $this->bio,
            'avatar_url' => $this->avatarUrl(),
            'content' => PublicUserGeneratedContentResource::collection($content),
        ]);
    }
}
