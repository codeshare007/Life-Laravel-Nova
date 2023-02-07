<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Wqa\NovaExtendFields\Fields\Textarea;
use Wqa\NovaSortableTableResource\Concerns\SortsIndexEntries;
use Wqa\NovaExtendFields\Fields\BelongsTo;

class CategoryTopTip extends Resource
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
            'order_by' => 'sort_order',
            'order_way' => 'asc'
        ];
    }

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\CategoryTopTip';

    /**
     * The logical group associated with the resource.
     *
     * @var string
     */
    public static $group = [];

    /**
     * Return the fields for the index view
     *
     * @return array
     */
    public function fieldsForIndex()
    {
        if (request()->editMode) {
            return [
                Textarea::make('Description'),
            ];
        }

        return [
            Textarea::make('Description'),
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
            Textarea::make('Description'),
            
            BelongsTo::make('Category'),
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
}
