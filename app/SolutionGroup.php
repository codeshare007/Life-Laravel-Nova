<?php

namespace App;

use App\Traits\ModelNameTrait;
use App\Traits\PolymorphicEventTrait;
use Illuminate\Database\Eloquent\Model;
use Fico7489\Laravel\Pivot\Traits\PivotEventTrait;

class SolutionGroup extends Model
{
    use PivotEventTrait;
    use ModelNameTrait;
    use PolymorphicEventTrait;

    protected $fillable = [
        'useable_id',
        'useable_type',
        'name',
    ];

    public function getUsesIconsAttribute()
    {
        return true;
    }

    public function getUsesApplicationAttribute()
    {
        return true;
    }

    public function getUsageDescriptionAttribute()
    {
        return $this->pivot->usage_description;
    }

    public function getUsageIdAttribute()
    {
        return true;
    }

    public function oils()
    {
        return $this->belongsToMany(Oil::class);
    }

    public function blends()
    {
        return $this->belongsToMany(Blend::class);
    }

    public function supplements()
    {
        return $this->belongsToMany(Supplement::class);
    }

    public function scopeFilterByType($builder, $type)
    {
        return $builder->where('type', $type);
    }

    public function ailment()
    {
        return $this->belongsTo(Ailment::class);
    }

    public function useable()
    {
        return $this->morphTo();
    }
}
