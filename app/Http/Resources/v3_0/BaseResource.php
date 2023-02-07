<?php

namespace App\Http\Resources\v3_0;

use Illuminate\Http\Resources\MissingValue;
use Illuminate\Http\Resources\Json\JsonResource;

abstract class BaseResource extends JsonResource
{
    protected function uuidArray($collection): ?array
    {
        return $this->pluck($collection, 'uuid');
    }

    protected function pluck($collection, string $key): ?array
    {
        if ($collection instanceof MissingValue) {
            return null;
        }

        return $collection->pluck($key)->filter()->toArray();
    }
}
