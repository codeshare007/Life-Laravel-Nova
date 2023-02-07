<?php

namespace App\Http\Resources\v2_1;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

/**
 * @OA\Schema(
 *     schema="CategoryRecipeResource2.1",
 *     description="Individual basic resource response",
 *     type="object",
 *     title="Category Recipe Resource V2.1",
 *     @OA\Property(
 *          property="type",
 *          type="string",
 *          description="Readable resource name"
 *     ),
 *     @OA\Property(
 *          property="uuid",
 *          type="integer",
 *          description="Resource UUID"
 *     ),
 *     @OA\Property(
 *          property="name",
 *          type="string",
 *          description="Resource name"
 *     ),
 *     @OA\Property(
 *          property="image_url",
 *          type="string",
 *          description="Resource image url"
 *     ),
 * )
 */
class CategoryRecipeResource extends JsonResource
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
            'uuid' => $this->uuid,
            'name' => $this->name,
            'type' => $this->getApiModelName()
        ];

        if (showFullDetails($request)) {
            $resource['body'] = $this->body ?? '';
            $resource['image_url'] = $this->image_url ? Storage::url($this->image_url) : '';
            $resource['user_id'] = $this->user_id ?? null;
        }

        return array_filter($resource);
    }
}
