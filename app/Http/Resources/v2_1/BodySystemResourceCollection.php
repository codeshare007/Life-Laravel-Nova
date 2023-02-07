<?php

namespace App\Http\Resources\v2_1;

use App\BodySystem;
use App\Services\PaginationAlphabeticalMetaData;
use Illuminate\Http\Resources\Json\ResourceCollection;

/**
 * @OA\Schema(
 *     schema="BodySystemCollectionResource2.1",
 *     description="Collection of ailments response",
 *     type="object",
 *     title="BodySystem Collection Resource V2.1",
 *     @OA\Property(
 *          property="data",
 *           @OA\Schema(
 *             type="array",
 *             @OA\Items(ref="#/components/schemas/BodySystemResource2.1")
 *         ),
 *     ),
 *     @OA\Property(
 *          property="alphabetical",
 *          description="Count of data alphabetically"
 *     )
 * )
 */
class BodySystemResourceCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $data = ['data' => $this->collection->filter()->all()];
//        if (isFirstPage($request)) {
//            $additionalMetaData = PaginationAlphabeticalMetaData::configureMetaData(
//                $request,
//                (new BodySystem)
//            );
//            if (!empty($additionalMetaData)) {
//                $data = array_merge($data, $additionalMetaData);
//            }
//        }

        return $data;
    }
}
