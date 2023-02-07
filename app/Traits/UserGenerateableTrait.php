<?php

namespace App\Traits; 

use App\User;

trait UserGenerateableTrait 
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getIsUserGeneratedAttribute(): bool
    {
        return $this->user_id && $this->user_id !== 0;
    }

    public function anonymise()
    {
        $lang = $this->user->language;

        $this->update([
            'user_id' => config('app.anonymous_user_id.' . $lang->value),
        ]);
    }
}