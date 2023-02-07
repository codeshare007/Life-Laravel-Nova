<?php

namespace App\Http\Resources\v2_1;

use Illuminate\Http\Resources\Json\ResourceCollection;

class RegionResourceCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return ['data' => $this->collection];
    }
}
