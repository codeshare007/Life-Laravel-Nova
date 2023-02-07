<?php

namespace App\Http\Resources\v2_1;

use App\Remedy;
use App\Services\PaginationAlphabeticalMetaData;
use Illuminate\Http\Resources\Json\ResourceCollection;

/**
 * @OA\Schema(
 *     schema="RemedyCollectionResource2.1",
 *     description="Collection of ailments response",
 *     type="object",
 *     title="Remedy Collection Resource V2.1",
 *     @OA\Property(
 *          property="data",
 *           @OA\Schema(
 *             type="array",
 *             @OA\Items(ref="#/components/schemas/RemedyResource2.1")
 *         ),
 *     ),
 *     @OA\Property(
 *          property="alphabetical",
 *          description="Count of data alphabetically"
 *     )
 * )
 */
class RemedyResourceCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $data = ['data' => $this->collection];
        if (isFirstPage($request)) {
            $additionalMetaData = PaginationAlphabeticalMetaData::configureMetaData(
                $request,
                (new Remedy)
            );
            if (!empty($additionalMetaData)) {
                $data = array_merge($data, $additionalMetaData);
            }
        }

        return $data;
    }
}
