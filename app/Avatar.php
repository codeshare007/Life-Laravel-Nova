<?php

namespace App;

use App\Traits\ImageableTrait;
use Illuminate\Database\Eloquent\Model;

class Avatar extends Model
{
    use ImageableTrait;

    protected $fillable = [
        'image_url',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
