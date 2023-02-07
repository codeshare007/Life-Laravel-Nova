<?php

namespace App\Observers;

use App\User;
use App\Avatar;
use App\NotificationSettings;

class UserObserver
{
    public function created(User $user)
    {
        NotificationSettings::create([
            'user_id' => $user->id,
        ]);

        if (! $user->avatar_id) {
            $user->avatar()->associate(Avatar::first());
            $user->save();
        }
    }

    public function deleting(User $user)
    {
        $user->collections->each->delete();
    }
}
