<?php

namespace App;

use App\Enums\TagType;
use App\Enums\ApiVersion;
use App\Enums\AilmentType;
use App\Traits\ImageableTrait;
use App\Traits\ModelNameTrait;
use App\Traits\ElementApiTrait;
use App\Traits\CollectableTrait;
use App\Traits\FavouriteableTrait;
use Illuminate\Database\Eloquent\Model;
use App\Traits\InvalidatesDeltaWhenDeletingTrait;

class BodySystem extends Model
{
    use FavouriteableTrait;
    use ImageableTrait;
    use CollectableTrait;
    use InvalidatesDeltaWhenDeletingTrait;
    use ElementApiTrait;
    use ModelNameTrait;

    protected $fillable = [
        'uuid',
        'name',
        'image_url',
        'color',
        'short_description',
        'usage_tip',
        'supplements',
    ];

    public static function getDefaultIncludes(ApiVersion $version): array
    {
        if ($version->is(ApiVersion::v2_1)) {
            return [
                'oils.solutionable',
                'blends.solutionable',
                'supplements.solutionable',
                'remedies',
                'ailments',
                'symptoms',
                'properties',
            ];
        }

        if ($version->is(ApiVersion::v3_0)) {
            return [
                'oils',
                'blends',
                'supplements',
                'remedies',
                'ailments',
                'symptoms',
                'properties',
            ];
        }

        return [];
    }

    public function solutions()
    {
        return $this->belongsToMany(Solution::class);
    }

    public function oils()
    {
        return $this->belongsToMany(Solution::class)->where('solutionable_type', Oil::class);
    }

    public function blends()
    {
        return $this->belongsToMany(Solution::class)->where('solutionable_type', Blend::class);
    }

    public function supplements()
    {
        return $this->belongsToMany(Solution::class)->where('solutionable_type', Supplement::class);
    }

    public function remedies()
    {
        return $this->belongsToMany(Remedy::class);
    }

    public function ailmentsAndSymptoms()
    {
        return $this->belongsToMany(Ailment::class);
    }

    public function ailments()
    {
        return $this->belongsToMany(Ailment::class)->where('ailment_type', AilmentType::Ailment);
    }

    public function symptoms()
    {
        return $this->belongsToMany(Ailment::class)->where('ailment_type', AilmentType::Symptom);
    }

    public function properties()
    {
        return $this->morphToMany(Tag::class, 'tagable')->where('type', TagType::Property);
    }

    public function getBodyAttribute()
    {
        return $this->short_description;
    }
}
