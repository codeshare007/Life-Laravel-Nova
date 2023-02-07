<?php

namespace App;

use App\Enums\TagType;
use App\Enums\ApiVersion;
use App\Traits\ImageableTrait;
use App\Traits\ElementApiTrait;
use App\Traits\RegionableTrait;
use App\Traits\CollectableTrait;
use App\Traits\FeatureableTrait;
use App\Traits\FavouriteableTrait;
use App\Traits\InvalidatesDeltaWhenDeletingTrait;

class Oil extends Model
{
    use FavouriteableTrait;
    use FeatureableTrait;
    use ImageableTrait;
    use CollectableTrait;
    use InvalidatesDeltaWhenDeletingTrait;
    use ElementApiTrait;
    use RegionableTrait;

    protected $table = 'oils';

    protected $fillable = [
        'uuid',
        'name',
        'latin_name',
        'image_url',
        'how_its_made',
        'emotion_1',
        'emotion_2',
        'emotion_3',
        'fact',
        'is_featured',
        'color',
        'research',
        'emotion_from',
        'emotion_to'
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'uses_application' => 'array',
    ];

    public static function getDefaultIncludes(ApiVersion $version): array
    {
        if ($version->is(ApiVersion::v2_1)) {
            return [
                'blendsWith',
                'foundInBlends',
                'foundIn.useable',
                'properties',
                'constituents',
                'sourcingMethods',
                'safetyInformation',
                'usages.ailments',
                'regionalNames',
            ];
        }

        if ($version->is(ApiVersion::v3_0)) {
            return [
                'blendsWith',
                'foundInBlends',
                'foundIn.useable',
                'properties',
                'constituents',
                'sourcingMethods',
                'safetyInformation',
                'usages',
                'regionalNames',
            ];
        }

        return [];
    }

    public function getUsesIconsAttribute()
    {
        return true;
    }

    public function getUsesApplicationAttribute()
    {
        return true;
    }

    public function usages()
    {
        return $this->morphMany(Usage::class, 'useable')->ordered();
    }

    public function blendsWith()
    {
        return $this->belongsToMany(Oil::class, 'oil_blends_with_oil', 'oil_id', 'blends_with_oil_id');
    }

    public function foundIn()
    {
        return $this->belongsToMany(SolutionGroup::class, 'oil_found_in_solution', 'oil_id', 'found_in_solution_id');
    }

    public function foundInBlends()
    {
        return $this->belongsToMany(Blend::class);
    }

    public function properties()
    {
        return $this->morphToMany(Tag::class, 'tagable')
            ->where('type', TagType::Property)
            ->orderBy('name');
    }

    public function constituents()
    {
        return $this->morphToMany(Tag::class, 'tagable')->where('type', TagType::Constituent);
    }

    public function sourcingMethods()
    {
        return $this->belongsToMany(SourcingMethod::class);
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
