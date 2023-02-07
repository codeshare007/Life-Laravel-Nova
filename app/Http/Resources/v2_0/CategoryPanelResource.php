<?php

namespace App\Http\Resources\v2_0;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="CategoryPanelResource2.0",
 *     description="Individual category panel response",
 *     type="object",
 *     title="Category Panel Resource V2.0",
 *     @OA\Property(
 *          property="resource_type",
 *          type="string",
 *          description="Readable resource name"
 *     ),
 *      @OA\Property(
 *          property="id",
 *          type="integer",
 *          description="Category panel Id"
 *     ),
 *     @OA\Property(
 *          property="title",
 *          type="string",
 *          description="Category panel title"
 *     ),
 *     @OA\Property(
 *          property="description",
 *          type="string",
 *          description="Category panel description"
 *     ),
 *     @OA\Property(
 *          property="background_image_url",
 *          type="string",
 *          description="Category panel background image path"
 *     ),
 *)
 */
class CategoryPanelResource extends JsonResource
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
            'resource_type' => 'CategoryPanel',
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'background_image_url' => $this->background_image_url ? Storage::url($this->background_image_url) : '',
        ];
    }
}
