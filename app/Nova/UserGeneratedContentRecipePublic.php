<?php

namespace App\Nova;

class UserGeneratedContentRecipePublic extends UserGeneratedContentRecipe
{
    public static function isPublic(): bool
    {
        return true;
    }
}
