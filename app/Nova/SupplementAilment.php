<?php

namespace App\Nova;

use App\Enums\AilmentType;
use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Naxon\NovaFieldSortable\Sortable;
use Wqa\NovaExtendFields\Fields\HasMany;
use Wqa\NovaExtendFields\Fields\Select;
use Wqa\NovaExtendFields\Fields\Text;
use Wqa\NovaExtendFields\Fields\Textarea;
use Wqa\NovaExtendFields\Fields\BelongsTo;
use Laravel\Nova\Http\Requests\NovaRequest;
use Wqa\NovaExtendFields\Fields\BelongsToMany;
use Wqa\NovaSortableTableResource\Concerns\SortsIndexEntries;

class SupplementAilment extends Resource
{
    use SortsIndexEntries;

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\SupplementAilment';

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
    public static $title = 'name';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'name',
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
        if (request()->editMode) {
            return [
                Text::make('Name'),
            ];
        }

        return [
            Text::make('Name'),
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
            Text::make('Name', 'name')
                ->rules('required'),

            Textarea::make('Description')
                ->rules('required'),

            Select::make('Ailment', 'ailment_id')
                ->options(
                    \App\Ailment::where('ailment_type', AilmentType::Ailment)->orderBy('name')->get()->pluck('name', 'id')
                )
                ->rules('required'),

            BelongsTo::make('Supplement')
                ->rules('required'),
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
