<?php

namespace App;

use App\Enums\ApiVersion;
use App\Traits\ImageableTrait;
use App\Traits\ModelNameTrait;
use App\Traits\ElementApiTrait;
use App\Traits\FavouriteableTrait;
use Illuminate\Database\Eloquent\Model;
use App\Traits\InvalidatesDeltaWhenDeletingTrait;

class Category extends Model
{
    use FavouriteableTrait;
    use ImageableTrait;
    use InvalidatesDeltaWhenDeletingTrait;
    use ElementApiTrait;
    use ModelNameTrait;

    protected $casts = [
        'type' => 'int',
    ];

    protected $fillable = [
        'uuid',
        'name',
        'type',
        'image_url',
        'color',
        'short_description',
    ];

    public static function getDefaultIncludes(ApiVersion $version): array
    {
        if ($version->is(ApiVersion::v2_1)) {
            return [
                'recipes',
                'topTips',
                'panels',
            ];
        }

        if ($version->is(ApiVersion::v3_0)) {
            return [
                'recipes',
            ];
        }

        return [];
    }

    public function recipes()
    {
        return $this->morphedByMany(Recipe::class, 'categoryable');
    }

    public function topTips()
    {
        return $this->hasMany(CategoryTopTip::class)->ordered();
    }

    public function panels()
    {
        return $this->hasMany(CategoryPanel::class)->ordered();
    }

    public function scopeFilterByType($builder, $type)
    {
        return $builder->where('type', $type);
    }

    public function getBodyAttribute()
    {
        return $this->short_description;
    }
}
