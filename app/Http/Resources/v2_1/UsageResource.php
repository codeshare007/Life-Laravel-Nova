<?php

namespace App\Http\Resources\v2_1;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="UsageResource2.1",
 *     description="Individual usage response",
 *     type="object",
 *     title="Usage Resource V2.1",
 *     @OA\Property(
 *          property="type",
 *          type="string",
 *          description="Readable resource name"
 *     ),
 *     @OA\Property(
 *          property="uuid",
 *          type="string",
 *          description="Usage uuid"
 *     ),
 *     @OA\Property(
 *          property="name",
 *          type="string",
 *          description="Usage name"
 *     )
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

        return array_filter([
            'name' => $this->name,
            'body' => $this->body,
            'uses_application' => $usesApplication ?? null,
            'elements' => $this->whenLoaded('ailments') ?
                OilUsageElementResource::collection($this->whenLoaded('ailments')) :
                null,
        ]);
    }
}
