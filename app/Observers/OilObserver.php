<?php

namespace App\Observers;

use App\Oil;

class OilObserver
{
    public function deleting(Oil $oil)
    {
        $oil->properties()->detach();
        $oil->usages()->get()->each->delete();
    }
}
