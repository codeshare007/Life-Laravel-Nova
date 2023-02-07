<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Wqa\NovaExtendFields\Fields\MorphTo;
use Wqa\NovaExtendFields\Fields\BelongsTo;
use Wqa\NovaSortableTableResource\Concerns\SortsIndexEntries;

class AilmentSecondarySolution extends Resource
{
    use SortsIndexEntries;
    
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\AilmentSecondarySolution';

    /**
     * Indicates if the resource should be displayed in the sidebar.
     *
     * @var bool
     */
    public static $displayInNavigation = false;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'id';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
    ];

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
            'order_by' => 'sort_order',
            'order_way' => 'asc'
        ];
    }

    /**
     * Return the fields for the index view
     *
     * @return array
     */
    public function fieldsForIndex()
    {
        return [
            MorphTo::make('Solution', 'solutionable')->types([
                Oil::class,
                Blend::class,
                Supplement::class,
            ]),
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
            BelongsTo::make('Ailment'),

            MorphTo::make('Solution', 'solutionable')->types([
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
