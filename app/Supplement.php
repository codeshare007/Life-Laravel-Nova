<?php

namespace App;

use App\Enums\ApiVersion;
use App\Traits\ImageableTrait;
use App\Traits\ElementApiTrait;
use App\Traits\RegionableTrait;
use App\Traits\CollectableTrait;
use App\Traits\FeatureableTrait;
use App\Traits\FavouriteableTrait;
use App\Traits\InvalidatesDeltaWhenDeletingTrait;

class Supplement extends Model
{
    use FavouriteableTrait;
    use FeatureableTrait;
    use ImageableTrait;
    use CollectableTrait;
    use InvalidatesDeltaWhenDeletingTrait;
    use ElementApiTrait;
    use RegionableTrait;

    protected $fillable = [
        'uuid',
        'name',
        'image_url',
        'fact',
        'is_featured',
        'color',
        'research',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
    ];

    public static function getDefaultIncludes(ApiVersion $version): array
    {
        if ($version->is(ApiVersion::v2_1)) {
            return [
                'supplementAilments.ailment',
                'supplementIngredients.ingredientable',
                'safetyInformation',
                'regionalNames',
            ];
        }

        if ($version->is(ApiVersion::v3_0)) {
            return [
                'supplementAilments.ailment',
                'supplementIngredients',
                'safetyInformation',
                'regionalNames',
            ];
        }

        return [];
    }

    public function supplementAilments()
    {
        return $this->hasMany(SupplementAilment::class);
    }

    public function supplementIngredients()
    {
        return $this->hasMany(SupplementIngredient::class);
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
