<?php

namespace App\Nova;

class UserGeneratedContentRecipePrivate extends UserGeneratedContentRecipe
{
    public static function isPublic(): bool
    {
        return false;
    }
}
