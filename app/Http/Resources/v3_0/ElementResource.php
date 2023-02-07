<?php

namespace App\Http\Resources\v3_0;

use App\Enums\ApiVersion;
use Illuminate\Http\Resources\Json\JsonResource;

class ElementResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        return $this->resourceByType()->toArray($request);
    }

    protected function resourceByType(): JsonResource
    {
        $model = $this->elementDetails;
        $model->loadMissing($model->getDefaultIncludes(ApiVersion::v3_0()));
        $resourceName = $this->elementDetails->getApiResource(ApiVersion::v3_0());

        return api_resource($resourceName)->make($model);
    }
}
