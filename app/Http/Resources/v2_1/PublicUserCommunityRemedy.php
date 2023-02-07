<?php

namespace App\Http\Resources\v2_1;

use App\Enums\UserGeneratedContentStatus;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="PublishUserCommunityRemdy2.1",
 *     description="Individual basic resource response",
 *     type="object",
 *     title="Publish User Community Remdy V2.1",
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
 * )
 */
class PublicUserCommunityRemedy extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        if (!$this->remedy) {
            return [];
        }

        return array_filter([
            'uuid' => $this->remedy->uuid,
            'type' => 'Remedy',
            'name' => $this->name,
            'instructions' => $this->instructions,
            'body_systems' => $this->remedy->bodySystems->map(function ($bodySystems) {
                return [
                    'uuid' => $bodySystems->uuid,
                    'name' => $bodySystems->name,
                ];
            })->all(),
            'ailments' => $this->remedy->ailment ?
                [[
                    'uuid' => $this->remedy->ailment->uuid,
                    'name' => $this->remedy->ailment->name,
                ]] :
                [],
        ]);
    }
}
