<?php

namespace App\Nova;

use App\Enums\FavouriteableModels;
use Illuminate\Http\Request;
use Wqa\NovaExtendFields\Fields\BelongsTo;
use Wqa\NovaExtendFields\Fields\MorphTo;

class Favourite extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\\Favourite';

    /**
     * Indicates if the resource should be displayed in the sidebar.
     *
     * @var bool
     */
    public static $displayInNavigation = false;

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [];

    /**
     * Determine if the given resource is authorizable.
     *
     * @return bool
     */
    public static function authorizable()
    {
        return false;
    }

    public function fieldsForIndex()
    {
        return [
            MorphTo::make('Favourited Resource', 'favouriteable'),
        ];
    }

    /**
     * Return the fields for left column
     *
     * @return array
     */
    public function leftColumnFields()
    {
        return [
            BelongsTo::make('User'),

            MorphTo::make('Favourited Resource', 'favouriteable')->types([
                Oil::class,
                Blend::class,
                Recipe::class,
                Remedy::class,
                BodySystem::class,
                Ailment::class,
                Category::class,
                Supplement::class,
            ]),
        ];
    }

    /**
     * Return the fields for right column
     *
     * @return array
     */
    public function rightColumnFields()
    {
        return [];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function lenses(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [];
    }

    /**
     * Return the tools
     *
     * @return array
     */
    protected function tools()
    {
        return [];
    }
}
