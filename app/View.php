<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class View extends Model
{
    protected $fillable = [
        'viewable_type',
        'viewable_id',
        'user_id'
    ];
}
