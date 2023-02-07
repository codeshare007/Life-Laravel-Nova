<?php

namespace App\Http\Resources\v2_1;

use App\Ailment;
use App\Nova\Symptom;
use App\Recipe;
use App\Remedy;
use Illuminate\Http\Resources\Json\JsonResource;

class FavouriteResource extends JsonResource
{
    /**
     * @OA\Schema(
     *     schema="FavouriteResource2.1",
     *     description="Individual basic resource response",
     *     type="object",
     *     title="Favourite Resource V2.1",
     *     @OA\Property(
     *          property="uuid",
     *          type="string",
     *          description="Favourited uuid"
     *     ),
     * )
     */
    public function toArray($request)
    {
        $response = [
            'type' => $this->favouriteable->getApiModelName(),
            'uuid' => $this->favouriteable->uuid,
            'name' => $this->favouriteable->name,
            'user_id' => $this->favouriteable->user_id ??
                null,
        ];

        switch ($this->favouriteable_type) {
            case Ailment::class:
                $response['body_systems'] = $this->favouriteable->bodySystems->map(function ($bodySystems) {
                    return [
                        'name' => $bodySystems->name,
                    ];
                })->all();
                break;
            case Recipe::class:
                $response['categories'] = $this->favouriteable->categories->map(function ($category) {
                    return [
                        'name' => $category->name,
                    ];
                })->all();
                break;
            case Remedy::class:
                $response['body_systems'] = $this->favouriteable->bodySystems->map(function ($bodySystems) {
                    return [
                        'name' => $bodySystems->name,
                    ];
                })->all();
                $response['ailments'] = $this->favouriteable->ailment ?
                    [[
                        'name' => $this->favouriteable->ailment->name,
                    ]]:
                    [];
                break;
        }

        return array_filter($response);
    }
}
