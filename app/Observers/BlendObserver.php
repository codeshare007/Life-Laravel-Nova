<?php

namespace App\Observers;

use App\Blend;

class BlendObserver
{
    public function deleted(Blend $blend)
    {
        $blend->usages()->get()->each->delete();
    }
}
