<?php

namespace App\Http\Resources\v2_1;

use App\Enums\ApiResource;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="SolutionGroupResource2.1",
 *     description="Individual solution response",
 *     type="object",
 *     title="Solution Group Resource V2.1",
 *)
 */
class SolutionGroupResource extends JsonResource
{
    /**
     * For the solution endpoint we combine
     * Oils, Blends and Supplements into a
     * single response
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        if (! $this->solutionable) {
            return [];
        }

        $resourceType = ApiResource::getValue(
            str_replace('App\\', '', $this->solutionable_type)
        );
        return api_resource($resourceType)->make($this->solutionable);
    }
}
