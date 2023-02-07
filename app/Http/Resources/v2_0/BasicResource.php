<?php

namespace App\Http\Resources\v2_0;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="BasicResource2.0",
 *     description="Individual basic resource response",
 *     type="object",
 *     title="Basic Resource V2.0",
 *     @OA\Property(
 *          property="resource_type",
 *          type="string",
 *          description="Readable resource name"
 *     ),
 *     @OA\Property(
 *          property="id",
 *          type="integer",
 *          description="Resource Id"
 *     ),
 *     @OA\Property(
 *          property="name",
 *          type="string",
 *          description="Resource name"
 *     ),
 *     @OA\Property(
 *          property="views_count",
 *          type="integer",
 *          description="Total view count on resource"
 *     ),
 *     @OA\Property(
 *          property="recent_views_count",
 *          type="integer",
 *          description="Total number of recent views for the resource"
 *     ),
 *     @OA\Property(
 *          property="is_user_generated",
 *          type="boolean",
 *          description="Flag if resource is user generated"
 *     ),
 * )
 */
class BasicResource extends JsonResource
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
            'resource_type' => $this->getResourceName(),
            'id' => $this->id,
            'name' => $this->name,
            'views_count' => 0,
            'recent_views_count' => 0,
            'is_user_generated' => $this->is_user_generated ?? false,
        ];

        return $resourceArr;
    }

    public function getResourceName()
    {
        $className = str_replace('App\\', '', get_class($this->resource));

        return $className . 'Basic';
    }
}
