<?php

namespace App\Http\Resources\v2_1;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

/**
 * @OA\Schema(
 *     schema="SymptomAilmentSecondarySolutionResource2.1",
 *     description="Individual ailment solution response",
 *     type="object",
 *     title="Symptom Ailment Secondary Solution Resource V2.1",
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
class SymptomAilmentSecondarySolutionResource extends JsonResource
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
            'uuid' => $this->solutionable->uuid,
            'name' => $this->solutionable->name,
        ];
        if (showFullDetails($request)) {
            $resource['type'] = $this->solutionable->getApiModelName();
        }

        return array_filter($resource);
    }
}
