<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Collectable extends Model
{
    protected $fillable = [
        'collectable_id',
        'collectable_type',
        'collection_id',
    ];

    public function scopeOrderByMostRecent($builder)
    {
        $builder->orderBy('created_at', 'desc');
    }

    public function collectable()
    {
        return $this->morphTo();
    }

    public function collection()
    {
        return $this->belongsTo(Collection::class);
    }
}
