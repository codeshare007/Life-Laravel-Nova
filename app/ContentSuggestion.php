<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ContentSuggestion extends Model
{
    protected $fillable = [
        'user_id',
        'association_id',
        'association_type',
        'name',
        'type',
        'mode',
        'content',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function association()
    {
        return $this->morphTo(
            null,
            null,
            'association_id',
            'uuid'
        );
    }
}
