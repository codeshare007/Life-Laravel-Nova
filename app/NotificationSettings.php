<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NotificationSettings extends Model
{
    protected $fillable = [
        'user_id',
        'enabled',
        'notify_for_favourites',
        'frequency',
    ];

    protected $casts = [
        'enabled' => 'boolean',
        'notify_for_favourites' => 'boolean',
        'frequency' => 'int',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
