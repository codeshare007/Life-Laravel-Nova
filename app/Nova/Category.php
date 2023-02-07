<?php

namespace App\Nova;

use Wqa\LinkField\Link;
use Illuminate\Http\Request;
use App\Nova\Actions\ForceUpdate;
use Wqa\DeepLinkTool\DeepLinkTool;
use Wqa\NovaExtendFields\Fields\Text;
use Wqa\NovaExtendFields\Fields\Image;
use Wqa\NovaExtendFields\Fields\HasMany;
use Wqa\NovaExtendFields\Fields\Textarea;
use Wqa\NovaExtendFields\MultiSelectField;
use Wqa\NovaSortableTableResource\Concerns\SortsIndexEntries;

class Category extends Resource
{
    use SortsIndexEntries;

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\Category';

    /**
     * The logical group associated with the resource.
     *
     * @var string
     */
    public static $group = ['Recipes'];

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
     * Return the fields for the index view.
     *
     * @return array
     */
    public function fieldsForIndex()
    {
        if (request()->editMode) {
            return [
                Text::make('Name'),

                Textarea::make('Short Description'),
            ];
        }

        return [
            Image::make('Photo', 'image_url', 's3'),

            Link::make('Name')->href('/admin/resources/categories/' . $this->id)->sortable(),

            Textarea::make('Short Description'),
        ];
    }

    /**
     * Return the fields for left column.
     *
     * @return array
     */
    public function leftColumnFields()
    {
        return [
            Text::make('Name'),

            Textarea::make('Short Description'),

            MultiSelectField::make('Recipes', 'recipes')
                ->withMeta(['selectedOptions' => $this->recipes->pluck('name', 'id')->mapToOptions()])
                ->options(\App\Recipe::orderBy('name')->pluck('name', 'id')->toArray())
                ->excludeOnUpdate()
                ->onlyOnForms(),

            HasMany::make('Panels', 'panels', CategoryPanel::class),

            HasMany::make('Top Tips', 'topTips', CategoryTopTip::class),
        ];
    }

    /**
     * Return the fields for right column.
     *
     * @return array
     */
    public function rightColumnFields()
    {
        return [
            Image::make('Photo', 'image_url', 's3')
                ->deletable(false)
                ->rules(['image', 'max:2000'])
                ->creationRules(['required'])
        ];
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
        return [
            new ForceUpdate(),
        ];
    }

    public function tools()
    {
        return [
            DeepLinkTool::make()->forResource('Category', $this->uuid ?? ''),
        ];
    }
}
