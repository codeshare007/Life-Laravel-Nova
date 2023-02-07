<?php

namespace App\Traits; 

trait FeatureableTrait 
{
    public function scopeFilterByFeatured($builder)
    {
        return $builder->where('is_featured', true);
    }
}