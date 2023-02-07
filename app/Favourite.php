<?php

namespace App;

use App\Traits\ModelNameTrait;
use App\Traits\PolymorphicEventTrait;
use Illuminate\Database\Eloquent\Model;

class Favourite extends Model
{
    use ModelNameTrait;
    use PolymorphicEventTrait;

    protected $fillable = [
        'favouriteable_type',
        'favouriteable_id',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function favouriteable()
    {
        return $this->morphTo();
    }

    public function scopeOrderByMostRecent($builder)
    {
        $builder->orderBy('created_at', 'desc');
    }
}
