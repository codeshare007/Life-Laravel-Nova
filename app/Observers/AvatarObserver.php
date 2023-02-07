<?php

namespace App\Observers;

use App\Avatar;

class AvatarObserver
{
    public function deleting(Avatar $avatar)
    {
        $defaultAvatar = Avatar::where('id', '!=', $avatar->id)->first();

        $avatar->users->each(function($user) use ($defaultAvatar) {
            $user->avatar()->associate($defaultAvatar);
            $user->save();
        });
    }
}
