<?php

namespace App;

use App\Enums\ApiVersion;
use App\Traits\ElementApiTrait;
use Illuminate\Database\Eloquent\Model;

class SourcingMethod extends Model
{
    use ElementApiTrait;

    public static function getDefaultIncludes(ApiVersion $version): array
    {
        return [];
    }

    protected $fillable = [
        'uuid',
        'name',
    ];
}
