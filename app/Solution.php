<?php

namespace App;

use App\Enums\ApiVersion;
use App\Traits\ModelNameTrait;
use App\Traits\ElementApiTrait;
use App\Traits\CollectableTrait;
use App\Traits\PolymorphicEventTrait;
use Illuminate\Database\Eloquent\Model;

class Solution extends Model
{
    use CollectableTrait;
    use ElementApiTrait;
    use ModelNameTrait;
    use PolymorphicEventTrait;

    protected $casts = [
        'type' => 'int',
    ];

    protected $fillable = [
        'uuid',
        'type',
        'name',
        'description',
    ];

    public static function getDefaultIncludes(ApiVersion $version): array
    {
        return [
            'solutionable'
        ];
    }

    public function solutionable()
    {
        return $this->morphTo();
    }

    public function body_systems()
    {
        return $this->belongsToMany(BodySystem::class);
    }

    public function getBodyAttribute()
    {
        return $this->description;
    }
}
