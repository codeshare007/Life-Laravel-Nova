<?php

namespace App\Nova;

use Wqa\NovaExtendFields\Fields\Text;
use Wqa\NovaExtendFields\Fields\Image;
use Wqa\NovaExtendFields\Fields\MorphTo;
use Wqa\NovaExtendFields\Fields\DateTime;
use Wqa\NovaExtendFields\Fields\BelongsTo;
use Wqa\NovaSortableTableResource\Concerns\SortsIndexEntries;

class UserSuggestedContent extends Resource
{
    use SortsIndexEntries;

    /**
     * Determine if the given resource is authorizable.
     *
     * @return bool
     */
    public static function authorizable()
    {
        return false;
    }

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\ContentSuggestion';

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'name';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
        'name',
    ];

    /**
     * The logical group associated with the resource.
     *
     * @var string
     */
    public static $group = ['  User Generated Content'];

    public static function label()
    {
        return 'Content Suggestions';
    }

    /**
     * Return the fields for the index view.
     *
     * @return array
     */
    public function fieldsForIndex()
    {
        return [
            Image::make('Photo', 'image_url', 's3'),

            Text::make('Type', function () {
                return preg_replace('/(?<!\ )[A-Z]/', ' $0', $this->type);
            }),

            Text::make('Name'),

            Text::make('Content'),

            MorphTo::make('Association'),

            BelongsTo::make('User'),

            DateTime::make('Created At')->format('MMM DD YYYY'),
        ];
    }
}
