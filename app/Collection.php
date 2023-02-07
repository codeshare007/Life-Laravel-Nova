<?php

namespace App;

use App\Traits\ImageableTrait;
use Illuminate\Database\Eloquent\Model;

class Collection extends Model
{
    use ImageableTrait;

    protected $fillable = [
        'name',
        'description',
        'user_id',
    ];

    protected $casts = [
        'user_id' => 'int',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function collectables()
    {
        return $this->hasMany(Collectable::class);
    }
}
