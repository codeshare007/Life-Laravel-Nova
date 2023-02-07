<?php

namespace App\Nova;

class UserGeneratedContentRemedyPrivate extends UserGeneratedContentRemedy
{
    public static function isPublic(): bool
    {
        return false;
    }
}
