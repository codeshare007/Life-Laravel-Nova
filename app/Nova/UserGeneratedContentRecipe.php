<?php

namespace App\Nova;

use App\Category;
use Wqa\NovaExtendFields\Fields\Text;
use Wqa\NovaExtendFields\Fields\Textarea;
use App\Nova\Filters\UserGeneratedContentStatus;

abstract class UserGeneratedContentRecipe extends UserGeneratedContent
{
    /**
     * Return the contentType value
     *
     * @return string
     */
    public static function contentType()
    {
        return 'Recipe';
    }

    /**
     * Return the fields for left column
     *
     * @return array
     */
    public function leftColumnFields()
    {
        return [
            Text::make('Name', 'name'),

            Textarea::make('Method', 'content.instructions')->withMeta(['extraAttributes' => [
                'disabled' => true,
            ]]),

            Text::make('Recipe Category', 'content.recipe_category_id', function() {
                return Category::findByIdOrUuid($this->content['recipe_category_id'])->name ?? '---';
            })->withMeta(['disabled' => true]),
        ];
    }
}
