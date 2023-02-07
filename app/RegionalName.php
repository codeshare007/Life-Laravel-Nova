<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RegionalName extends Model
{
    protected $fillable = [
        'name',
        'region_id',
        'regionable_type',
        'regionable_id',
    ];

    public function regionable()
    {
        return $this->morphTo();
    }
}
