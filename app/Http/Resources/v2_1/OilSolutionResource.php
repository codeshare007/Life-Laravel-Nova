<?php

namespace App\Http\Resources\v2_1;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

/**
 * @OA\Schema(
 *     schema="OilSolutionResource2.1",
 *     description="Individual oil solution response",
 *     type="object",
 *     title="Oil Solution Resource V2.1",
 *     @OA\Property(
 *          property="uuid",
 *          type="string",
 *          description="Oil solution UUID"
 *     ),
 *     @OA\Property(
 *          property="name",
 *          type="string",
 *          description="Oil solution name"
 *     ),
 *     @OA\Property(
 *          property="image_url",
 *          type="string",
 *          description="Oil image url"
 *     ),
 *)
 */
class OilSolutionResource extends JsonResource
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
            'uuid' => $this->useable->uuid,
            'name' => $this->useable->name,
            'image_url' => $this->useable->image_url ? Storage::url($this->useable->image_url) : '',
            'type' => $this->useable ?
                $this->useable->getApiModelName() :
                null,
        ];

        if (showFullDetails($request)) {
            $resource['body'] = $this->body ?? '';
        }

        return array_filter($resource);
    }
}
