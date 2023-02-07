<?php

namespace App\Services\SeedData;

use App\Enums\ApiVersion;
use App\Enums\v3_0\ElementType;
use Illuminate\Http\Resources\Json\JsonResource;

class ElementsSeedData extends BaseSeedData
{
    public function build(): BaseSeedData
    {
        $this->seedData = collect(ElementType::toArray())->map(function($modelName) {
            return $this->resourceCollectionByModelType($modelName)->jsonSerialize();
        })->flatten(1);

        return $this;
    }

    protected function resourceCollectionByModelType($modelName): JsonResource
    {
        $model = new $modelName;
        $collection = $model->withDefaultIncludes(ApiVersion::v3_0())->get();
        $resourceName = $model->getApiResource(ApiVersion::v3_0());

        return api_resource($resourceName)->collection($collection);
    }
}
