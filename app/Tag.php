<?php

namespace App;

use App\Enums\TagType;
use App\Enums\ApiVersion;
use App\Enums\ApiResource;
use App\Enums\ElementCacheKey;
use App\Traits\ElementApiTrait;
use App\Traits\FavouriteableTrait;
use Spatie\EloquentSortable\Sortable;
use Illuminate\Database\Eloquent\Model;
use Spatie\EloquentSortable\SortableTrait;
use App\Traits\InvalidatesDeltaWhenDeletingTrait;

class Tag extends Model implements Sortable
{
    use SortableTrait;
    use InvalidatesDeltaWhenDeletingTrait;
    use FavouriteableTrait;
    use ElementApiTrait;

    public $sortable = [
        'order_column_name' => 'sort_order',
        'sort_when_creating' => true,
    ];

    protected $fillable = [
        'uuid',
        'name',
        'type',
    ];

    protected $casts = [
        'type' => 'int',
    ];

    public static function getDefaultIncludes(ApiVersion $version): array
    {
        return [
            'oils',
        ];
    }

    public function getApiResource(ApiVersion $version)
    {
        if ($version->is(ApiVersion::v3_0)) {
            return 'TagResource';
        }

        return ApiResource::Tag;
    }

    public function getApiModelName()
    {
        return $this->type == TagType::Constituent ?
            'Constituent' :
            'Property';
    }

    public function scopeFilterByType($builder, $type)
    {
        return $builder->where('type', $type);
    }

    public function oils()
    {
        return $this->morphedByMany(Oil::class, 'tagable');
    }
}
