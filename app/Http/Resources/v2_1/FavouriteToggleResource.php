<?php

namespace App\Http\Resources\v2_1;

use Illuminate\Http\Resources\Json\JsonResource;

class FavouriteToggleResource extends JsonResource
{
    /**
     * @OA\Schema(
     *     schema="FavouriteToggleResource2.1",
     *     description="Individual basic resource response",
     *     type="object",
     *     title="Favourite Toggle Resource V2.1",
     *     @OA\Property(
     *          property="uuid",
     *          type="string",
     *          description="Favourited uuid"
     *     ),
     * )
     */
    public function toArray($request)
    {
        return [];
    }
}
