<?php

namespace App\Http\Resources\v2_1;

use App\Enums\UserGeneratedContentStatus;
use App\Recipe;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;
use Route;

/**
 * @OA\Schema(
 *     schema="UserGeneratedContentResource2.1",
 *     description="Individual ailment remedy resource response",
 *     type="object",
 *     title="User Generated Content Resource V3.0",
 *     @OA\Property(
 *          property="uuid",
 *          type="integer",
 *          description="Resource UUID"
 *     ),
 *     @OA\Property(
 *          property="id",
 *          type="integer"
 *     ),
 *     @OA\Property(
 *          property="public_uuid",
 *          type="integer"
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
 *     @OA\Property(
 *          property="type",
 *          type="string",
 *          description="Readable resource name"
 *     ),
 *     @OA\Property(
 *          property="status",
 *          type="string"
 *     ),
 *     @OA\Property(
 *          property="is_public",
 *          type="boolean"
 *     ),
 *     @OA\Property(
 *          property="content",
 *          type="array",
 *          @OA\Items()
 *     ),
 *     @OA\Property(
 *          property="rejection_reason_subject",
 *          type="string"
 *     ),
 *     @OA\Property(
 *          property="rejection_reason_description",
 *          type="string"
 *     ),
 * )
 */
class UserGeneratedContentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        if ($this->type == 'Recipe') {
            return UserCommunityRecipe::make($this);
        } else {
            return UserCommunityRemedy::make($this);
        }
    }
}
