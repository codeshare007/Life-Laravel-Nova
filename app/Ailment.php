<?php

namespace App;

use App\Enums\ApiVersion;
use App\Enums\AilmentType;
use App\Enums\ApiResource;
use App\Enums\ElementCacheKey;
use App\Traits\ImageableTrait;
use App\Traits\ElementApiTrait;
use App\Traits\CollectableTrait;
use App\Traits\FeatureableTrait;
use App\Traits\FavouriteableTrait;
use App\Traits\InvalidatesDeltaWhenDeletingTrait;

class Ailment extends Model
{
    use FavouriteableTrait;
    use FeatureableTrait;
    use ImageableTrait;
    use CollectableTrait;
    use InvalidatesDeltaWhenDeletingTrait;
    use ElementApiTrait;

    protected $fillable = [
        'uuid',
        'name',
        'color',
        'image_url',
        'short_description',
        'is_featured',
        'ailment_type'
    ];

    protected $casts = [
        'is_featured' => 'boolean',
    ];

    public static function getDefaultIncludes(ApiVersion $version): array
    {
        if ($version->is(ApiVersion::v2_1)) {
            return [
                'bodySystems',
                'remedies',
                'solutions.solutionable',
                'secondarySolutions.solutionable',
                'relatedAilments',
                'relatedBodySystems',
                'symptoms',
            ];
        }

        if ($version->is(ApiVersion::v3_0)) {
            return [
                'bodySystems',
                'remedies',
                'solutions',
                'secondarySolutions.solutionable',
                'relatedAilments',
                'relatedBodySystems',
                'symptoms',
            ];
        }

        return [];
    }

    public function getApiResource(ApiVersion $version)
    {
        return $this->ailment_type == AilmentType::Ailment ?
            ApiResource::Ailment :
            ApiResource::Symptom;
    }

    public function getApiModelName()
    {
        return $this->ailment_type == AilmentType::Ailment ?
            'Ailment' :
            'Symptom';
    }

    public function getApiCacheName()
    {
        return $this->ailment_type == AilmentType::Ailment ?
            ElementCacheKey::Ailment :
            ElementCacheKey::Symptom;
    }

    public function bodySystems()
    {
        return $this->belongsToMany(BodySystem::class);
    }

    public function relatedBodySystems()
    {
        return $this->belongsToMany(BodySystem::class, 'ailment_related_body_system');
    }

    public function remedies()
    {
        return $this->hasMany(Remedy::class);
    }

    public function solutions()
    {
        return $this->hasMany(AilmentSolution::class)->ordered();
    }

    public function secondarySolutions()
    {
        return $this->hasMany(AilmentSecondarySolution::class)->ordered();
    }

    public function oils()
    {
        return $this->belongsToMany(Oil::class)->withPivot([
            'sort_order',
            'uses_application',
        ]);
    }

    public function blends()
    {
        return $this->belongsToMany(Blend::class)->withPivot([
            'sort_order',
            'uses_application',
        ]);
    }

    public function supplements()
    {
        return $this->belongsToMany(Supplement::class)->withPivot([
            'sort_order',
            'uses_application',
        ]);
    }

    public function relatedAilments()
    {
        return $this->belongsToMany(Ailment::class, 'ailment_related_ailment', 'ailment_id', 'related_ailment_id');
    }

    public function symptoms()
    {
        return $this->belongsToMany(Ailment::class, 'ailment_symptom', 'ailment_id', 'symptom_id');
    }

    // This is the reverse of symptoms
    public function symptomAilments()
    {
        return $this->belongsToMany(Ailment::class, 'ailment_symptom', 'symptom_id', 'ailment_id');
    }

    public function usages()
    {
        return $this->belongsToMany(Usage::class);
    }

    public function getBodyAttribute()
    {
        return $this->short_description;
    }
}
