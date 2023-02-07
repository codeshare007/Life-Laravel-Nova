<?php

namespace App\Http\Resources\v2_1;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

/**
 * @OA\Schema(
 *     schema="AilmentSecondarySolutionResource2.1",
 *     description="Individual ailment secondary solution response",
 *     type="object",
 *     title="Ailment Secondary Solution Resource V2.1",
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
class AilmentSecondarySolutionResource extends JsonResource
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
            'image_url' => $this->solutionable->image_url ? Storage::url($this->solutionable->image_url) : '',
        ];

        if (showFullDetails($request)) {
            $resource['type'] = $this->solutionable->getApiModelName();
            $resource['body'] = $this->body ?? '';
        }

        return array_filter($resource);
    }
}
