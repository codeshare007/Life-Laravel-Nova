<?php

namespace App;

use App\Enums\ApiVersion;
use App\Traits\ImageableTrait;
use App\Traits\ElementApiTrait;
use App\Traits\RegionableTrait;
use App\Traits\CollectableTrait;
use App\Traits\FavouriteableTrait;
use Spatie\EloquentSortable\SortableTrait;
use App\Traits\InvalidatesDeltaWhenDeletingTrait;

class Blend extends Model
{
    use FavouriteableTrait;
    use ImageableTrait;
    use CollectableTrait;
    use InvalidatesDeltaWhenDeletingTrait;
    use ElementApiTrait;
    use RegionableTrait;
    use SortableTrait;

    protected $fillable = [
        'uuid',
        'name',
        'image_url',
        'emotion_1',
        'emotion_2',
        'emotion_3',
        'fact',
        'color',
        'sort_order'
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'uses_application' => 'array',
    ];

    public static function getDefaultIncludes(ApiVersion $version): array
    {
        if ($version->is(ApiVersion::v2_1)) {
            return [
                'ingredients',
                'safetyInformation',
                'usages.ailments',
                'regionalNames',
            ];
        }

        if ($version->is(ApiVersion::v3_0)) {
            return [
                'ingredients',
                'safetyInformation',
                'usages',
                'regionalNames',
            ];
        }

        return [];
    }

    public function usages()
    {
        return $this->morphMany(Usage::class, 'useable')->ordered();
    }

    public function ingredients()
    {
        return $this->belongsToMany(Oil::class);
    }

    public function safetyInformation()
    {
        return $this->belongsTo(SafetyInformation::class);
    }

    public function solutions()
    {
        return $this->belongsToMany(Solution::class);
    }
}
