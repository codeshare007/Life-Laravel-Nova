<?php

namespace App;

use App\Enums\ApiVersion;
use App\Traits\ElementApiTrait;
use Spatie\EloquentSortable\SortableTrait;

class Usage extends Model
{
    use SortableTrait;
    use ElementApiTrait;

    public $sortable = [
        'order_column_name' => 'sort_order',
        'sort_when_creating' => true,
    ];

    protected $fillable = [
        'uuid',
        'name',
        'description',
        'useable_type',
        'useable_id',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function (Usage $model) {
            if (!$model->uses_application) {
                $model->uses_application = self::defaultUsesApplication();
            }
        });
    }

    public static function getDefaultIncludes(ApiVersion $version): array
    {
        if ($version->is(ApiVersion::v3_0)) {
            return [
                'useable',
                'ailments',
            ];
        }

        return [];
    }

    public function ailments()
    {
        return $this->belongsToMany(Ailment::class);
    }

    public function useable()
    {
        return $this->morphTo();
    }

    public function getBodyAttribute()
    {
        return $this->description;
    }

    public static function defaultUsesApplication()
    {
        return '[{"name":"Internal","active":false,"position":0},{"name":"Topical","active":false,"position":1},{"name":"Aromatic","active":false,"position":2}]';
    }
}
