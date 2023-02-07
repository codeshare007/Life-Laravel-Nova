<?php

namespace App\Http\Resources\v3_0;

/**
 * @OA\Schema(
 *     schema="UserResource3.0",
 *     description="Individual user response",
 *     type="object",
 *     title="User Resource V3.0",
 *     @OA\Property(
 *          property="id",
 *          type="integer"
 *     ),
 *     @OA\Property(
 *          property="name",
 *          type="string"
 *     ),
 *     @OA\Property(
 *          property="bio",
 *          type="string"
 *     ),
 *     @OA\Property(
 *          property="avatar_url",
 *          type="string"
 *     ),
 *     @OA\Property(
 *          property="content",
 *          type="array",
 *          @OA\Items()
 *     )
 *)
 */
class UserResource extends BaseResource
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
            'id' => $this->id,
            'name' => $this->name,
            'bio' => $this->bio,
            'avatar_url' => $this->avatarUrl(),
            'content' => $this->publicUgcUuids(),
        ]);
    }

    protected function publicUgcUuids(): array
    {
        if ($this->resource->relationLoaded('content')) {
            return $this->content->map(function($ugc) {
                return $ugc->publicModel->uuid;
            })->toArray();
        }
        
        return [];
    }
}
