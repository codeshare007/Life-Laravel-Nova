<?php

namespace App;

use App\Enums\ApiVersion;
use App\Enums\ApiResource;
use App\Traits\ModelNameTrait;
use App\Traits\ElementApiTrait;
use App\Traits\PolymorphicEventTrait;
use Spatie\EloquentSortable\Sortable;
use Illuminate\Database\Eloquent\Model;
use Spatie\EloquentSortable\SortableTrait;

class SupplementIngredient extends Model implements Sortable
{
    use SortableTrait;
    use ElementApiTrait;
    use ModelNameTrait;
    use PolymorphicEventTrait;

    public $sortable = [
        'order_column_name' => 'sort_order',
        'sort_when_creating' => true,
    ];

    protected $guarded = [
        'id',
    ];

    public static function getDefaultIncludes(ApiVersion $version): array
    {
        if ($version->is(ApiVersion::v3_0)) {
            return [
                'ingredientable',
            ];
        }

        return [];
    }

    public function getApiResource(ApiVersion $version)
    {
        if ($version->is(ApiVersion::v3_0)) {
            return 'IngredientResource';
        }

        return ApiResource::SupplementIngredient;
    }

    public function ingredientable()
    {
        return $this->morphTo();
    }

    public function supplement()
    {
        return $this->belongsTo(Supplement::class);
    }
}
