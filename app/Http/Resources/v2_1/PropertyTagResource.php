<?php

namespace App\Http\Resources\v2_1;

use App\Enums\TagType;
use Illuminate\Http\Resources\Json\JsonResource;
/**
 * @OA\Schema(
 *     schema="PropertyTagResource2.1",
 *     description="Individual property tag response",
 *     type="object",
 *     title="Property Tag Resource V2.1",
 *)
 */
class PropertyTagResource extends JsonResource
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
            'type' => $this->getApiModelName(),
            'uuid' => $this->uuid,
            'name' => $this->name,
            'oils' => BasicResource::collection($this->whenLoaded('oils')),
        ];

        if (showFullDetails($request)) {
           $resource['id'] = $this->id;
        }

        return array_filter($resource);
    }
}
