<?php

namespace App\Traits; 

use App\View;

trait ViewableTrait 
{
    public static function bootViewableTrait() 
    {
        static::deleting(function ($model) {
            static::deleteViewsForModel($model);
        });
    }

    public function scopeOrderByTotalViews($builder)
    {
        $builder->withCount('views')->orderBy('views_count', 'desc');
    }

    public function scopeOrderByRecentViews($builder)
    {
        $builder->withCount('recentViews')->orderBy('recent_views_count', 'desc');
    }

    public function views()
    {
        return $this->morphMany(View::class, 'viewable');
    }

    public function recentViews()
    {
        $days = 30; // The number of days to look back
        $daysAgo = now()->subDays($days);

        return $this->morphMany(View::class, 'viewable')->where('created_at', '>=', $daysAgo);
    }

    private static function deleteViewsForModel($model)
    {
        View::where([
            'viewable_id' => $model->id,
            'viewable_type' => get_class($model),
        ])->delete();
    }
}