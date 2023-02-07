<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\Text;
use Wqa\NovaExtendFields\Fields\MorphTo;
use Wqa\NovaSortableTableResource\Concerns\SortsIndexEntries;

class SolutionGroup extends Resource
{
    use SortsIndexEntries;

    /**
     * Create a new resource instance.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $resource
     * @return void
     */
    public function __construct($resource)
    {
        parent::__construct($resource);

        $this->defaultSortBy = [
            'order_by' => 'id',
            'order_way' => 'asc'
        ];
    }

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\SolutionGroup';

    public static $displayInNavigation = false;

    /**
     * The logical group associated with the resource.
     *
     * @var string
     */
    public static $group = ['Solutions'];

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public function title()
    {
        return $this->useable->name;
    }

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
     * Return the fields for the index view
     *
     * @return array
     */
    public function fieldsForIndex()
    {
        if (request()->editMode) {
            return [
                MorphTo::make('Related Oil/Blend/Supplement', 'useable')->types([
                    Oil::class,
                    Blend::class,
                    Supplement::class,
                ]),
            ];
        }

        return [
            Text::make('Name', function () {
                return $this->useable->name;
            }),

            MorphTo::make('Related Oil/Blend/Supplement', 'useable')->types([
                Oil::class,
                Blend::class,
                Supplement::class,
            ])
            ->onlyOnForms(),
        ];
    }

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function leftColumnFields()
    {
        return [
            MorphTo::make('Related Oil/Blend/Supplement', 'useable')->types([
                Oil::class,
                Blend::class,
                Supplement::class,
            ]),
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
        return [];
    }
}
