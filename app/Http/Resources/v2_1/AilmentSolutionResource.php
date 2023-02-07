<?php

namespace App\Http\Resources\v2_1;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

/**
 * @OA\Schema(
 *     schema="AilmentSolutionResource2.1",
 *     description="Individual ailment solution response",
 *     type="object",
 *     title="Ailment Solution Resource V2.1",
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
 *)
 */
class AilmentSolutionResource extends JsonResource
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

        $resource = [
            'uuid' => $this->solutionable->uuid,
            'name' => $this->solutionable->name,
        ];
        if (showFullDetails($request)) {
            $resource['type'] = $this->solutionable->getApiModelName();
            $resource['body'] = $this->uses_description ?? '';
            $resource['uses_application'] = $usesApplication ?? null;
        }

        return array_filter($resource);
    }
}
