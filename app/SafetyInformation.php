<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SafetyInformation extends Model
{
    protected $fillable = [
        'description',
    ];

    public function setDescriptionAttribute($description) 
    {
        $this->attributes['description'] = trim(strip_tags($description));
    }
}
