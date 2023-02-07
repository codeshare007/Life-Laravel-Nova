<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    protected $fillable = [
        'likeable_type',
        'likeable_id',
        'user_id',
    ];
}
