<?php

namespace App\Http\Resources\v2_1;

use App\Ailment;
use App\Enums\AilmentType;
use App\Services\PaginationAlphabeticalMetaData;
use Illuminate\Http\Resources\Json\ResourceCollection;

/**
 * @OA\Schema(
 *     schema="SymptomCollectionResource2.1",
 *     description="Collection of ailments response",
 *     type="object",
 *     title="Symptom Collection Resource V2.1",
 *     @OA\Property(
 *          property="data",
 *          @OA\Schema(
 *              type="array",
 *              @OA\Items(ref="#/components/schemas/SymptomResource2.1")
 *          ),
 *     ),
 *     @OA\Property(
 *          property="alphabetical",
 *          description="Count of data alphabetically"
 *     )
 * )
 */
class SymptomResourceCollection extends ResourceCollection
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
                Ailment::where('ailment_type', AilmentType::Symptom)
            );
            if (!empty($additionalMetaData)) {
                $data = array_merge($data, $additionalMetaData);
            }
        }

        return $data;
    }
}
