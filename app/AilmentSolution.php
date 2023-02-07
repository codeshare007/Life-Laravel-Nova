<?php

namespace App;

use App\Enums\ApiVersion;
use App\Traits\ElementApiTrait;
use App\Traits\PolymorphicEventTrait;

class AilmentSolution extends Model
{
    use ElementApiTrait;
    use PolymorphicEventTrait;

    protected $fillable = [
        'uses_description',
        'uses_application',
    ];

    /**
     * All of the relationships to be touched.
     *
     * @var array
     */
    protected $touches = [
        'ailment'
    ];

    public static function getDefaultIncludes(ApiVersion $version): array
    {
        if ($version->is(ApiVersion::v3_0)) {
            return [
                'solutionable',
            ];
        }

        return [];
    }

    public function solutionable()
    {
        return $this->morphTo();
    }

    public function ailment()
    {
        return $this->belongsTo(Ailment::class);
    }

    public function getUsesApplicationListAttribute()
    {
        return collect(json_decode($this->uses_application, true))
            ->whereIn('active', true)
            ->sortBy('position')->map(function ($item, $key) {
                return $item['name'];
            })->values();
    }

    public function getBodyAttribute()
    {
        return $this->uses_description;
    }
}
