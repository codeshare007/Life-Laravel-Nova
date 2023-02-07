<?php

namespace App\Nova;

class UserGeneratedContentRemedyPublic extends UserGeneratedContentRemedy
{
    public static function isPublic(): bool
    {
        return true;
    }
}
