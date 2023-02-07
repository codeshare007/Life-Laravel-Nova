<?php

namespace App;

use App\Enums\Region;
use App\Enums\Platform;
use Spatie\EloquentSortable\Sortable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Spatie\EloquentSortable\SortableTrait;

class Card extends Model implements Sortable
{
    use SortableTrait;

    public $fillable = [
        'title',
        'subtitle',
        'description',
    ];

    public $sortable = [
        'order_column_name' => 'sort_order',
        'sort_when_creating' => true,
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_visible_on_ios' => 'boolean',
        'is_visible_on_android' => 'boolean',
        'regions' => 'array',
    ];

    public function scopeActive(Builder $query)
    {
        return $query->where('is_active', true);
    }

    public function scopeForPlatform(Builder $query, Platform $platform)
    {
        if ($platform->is(Platform::iOS)) {
            return $query->where('is_visible_on_ios', true);
        }

        if ($platform->is(Platform::Android)) {
            return $query->where('is_visible_on_android', true);
        }
    }

    public function scopeForRegion(Builder $query, Region $region)
    {
        return $query->whereJsonContains('regions', $region->value);
    }
}
