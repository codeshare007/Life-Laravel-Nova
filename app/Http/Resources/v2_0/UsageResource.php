<?php

namespace App\Http\Resources\v2_0;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="UsageResource2.0",
 *     description="Individual usage response",
 *     type="object",
 *     title="Usage Resource V2.0",
 *     @OA\Property(
 *          property="resource_type",
 *          type="string",
 *          description="Readable resource name"
 *     ),
 *     @OA\Property(
 *          property="id",
 *          type="integer",
 *          description="Usage Id"
 *     ),
 *     @OA\Property(
 *          property="name",
 *          type="string",
 *          description="Usage name"
 *     ),
 *     @OA\Property(
 *          property="description",
 *          type="string",
 *          description="Usage description"
 *     ),
 *     @OA\Property(
 *          property="uses_application",
 *          type="array",
 *          description="Usage applications",
 *          @OA\Items(type="string")
 *     ),
 *     @OA\Property(
 *          property="views_count",
 *          type="integer",
 *          description="Total view count on the usage"
 *     ),
 *     @OA\Property(
 *          property="recent_views_count",
 *          type="integer",
 *          description="recent view count on usage"
 *     ),
 *     @OA\Property(
 *          property="ailments",
 *          type="array",
 *          description="A list of the usages ailments",
 *          @OA\Items(ref="#/components/schemas/BasicResource2.0")
 *     ),
 *)
 */
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
