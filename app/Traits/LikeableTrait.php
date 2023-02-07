<?php

namespace App\Traits; 

use App\Like;

trait LikeableTrait
{
    public static function bootLikeableTrait() 
    {
        static::deleting(function ($model) {
            static::deleteLikesForModel($model);
        });
    }

    public function likes()
    {
        return $this->morphMany(Like::class, 'likeable');
    }

    public function scopeOrderByTotalLikes($builder)
    {
        $builder->withCount('likes')->orderBy('likes_count', 'desc');
    }

    private static function deleteLikesForModel($model)
    {
        Like::where([
            'likeable_id' => $model->id,
            'likeable_type' => get_class($model),
        ])->delete();
    }
}