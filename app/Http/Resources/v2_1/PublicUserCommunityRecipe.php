<?php

namespace App\Http\Resources\v2_1;

use App\Enums\UserGeneratedContentStatus;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="PublishUserCommunityRecipe2.1",
 *     description="Individual basic resource response",
 *     type="object",
 *     title="Publish User Community Recipe V2.1",
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
class PublicUserCommunityRecipe extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        if (!$this->recipe) {
            return [];
        }

        return array_filter([
            'uuid' => $this->recipe->uuid,
            'type' => 'Recipe',
            'name' => $this->name,
            'instructions' => $this->instructions,
            'categories'=> $this->recipe ?
                $this->recipe->categories->map(function ($category) {
                    return [
                        'uuid' => $category->uuid,
                        'name' => $category->name,
                    ];
                })->all()
                :
                [],
        ]);
    }
}
