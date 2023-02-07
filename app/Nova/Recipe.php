<?php

namespace App\Nova;

use Wqa\LinkField\Link;
use Illuminate\Http\Request;
use App\Nova\Actions\ForceUpdate;
use Wqa\DeepLinkTool\DeepLinkTool;
use App\Nova\Filters\IsUserGenerated;
use Wqa\NovaExtendFields\Fields\Text;
use Wqa\NovaExtendFields\Fields\Image;
use Wqa\NovaExtendFields\Fields\Number;
use Wqa\NovaExtendFields\Fields\HasMany;
use Wqa\NovaExtendFields\Fields\DateTime;
use Wqa\NovaExtendFields\Fields\Textarea;
use Wqa\NovaExtendFields\Fields\BelongsTo;
use Wqa\NovaExtendFields\MultiSelectField;
use Wqa\NovaExtendFields\Fields\BooleanSwitch;
use Wqa\NovaSortableTableResource\Concerns\SortsIndexEntries;

class Recipe extends Resource
{
    use SortsIndexEntries;

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\Recipe';

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
        'name'
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

                Textarea::make('Method', 'body'),

                BooleanSwitch::make('Featured', 'is_featured'),
            ];
        }

        return [
            Image::make('Photo', 'image_url', 's3'),

            Link::make('Name')->href('/admin/resources/recipes/' . $this->id)->sortable(),

            Textarea::make('Method', 'body'),

            BooleanSwitch::make('Featured', 'is_featured')->sortable(),

            BelongsTo::make('User'),

            DateTime::make('Created At')->format('MMM DD YYYY')->sortable(),

            Number::make('Favourites Count', function () {
                return $this->favourites()->count();
            }),
        ];
    }

    /**
     * Return the fields for left column.
     *
     * @return array
     */
    protected function leftColumnFields()
    {
        return [
            BooleanSwitch::make('Make Featured', 'is_featured')->hideLabel(),

            Text::make('Recipe Name', 'name'),

            Textarea::make('Method', 'body'),

            MultiSelectField::make('Categories', 'categories')
                ->withMeta(['selectedOptions' => $this->categories->pluck('name', 'id')->mapToOptions()])
                ->options(\App\Category::orderBy('name')->pluck('name', 'id')->toArray())
                ->excludeOnUpdate(),

            MultiSelectField::make('Related Recipes', 'related_recipes')
                ->withMeta(['selectedOptions' => $this->relatedRecipes->pluck('name', 'id')->mapToOptions()])
                ->options(\App\Recipe::orderBy('name')->pluck('name', 'id')->toArray())
                ->excludeOnUpdate(),

            HasMany::make('Ingredients', 'recipeIngredients', RecipeIngredient::class),
        ];
    }

    /**
     * Return the fields for right column.
     *
     * @return array
     */
    protected function rightColumnFields()
    {
        return [
            Image::make('Photo', 'image_url', 's3')
                ->deletable(false)
                ->rules(['image', 'max:2000'])
                ->creationRules(['required']),

            BelongsTo::make('User')->nullable()->searchable(),
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
        return [
            new IsUserGenerated(),
        ];
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
            DeepLinkTool::make()->forResource('Recipe', $this->uuid ?? ''),
        ];
    }
}
