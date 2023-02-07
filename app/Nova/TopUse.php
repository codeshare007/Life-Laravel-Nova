<?php

namespace App\Nova;

use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Wqa\UsesIcons\UsesIcons;
use Laravel\Nova\Fields\Trix;
use Laravel\Nova\Fields\Select;
use Naxon\NovaFieldSortable\Sortable;
use Wqa\NovaExtendFields\Fields\Text;
use Wqa\NovaExtendFields\Fields\MorphTo;
use Wqa\NovaExtendFields\Fields\Textarea;
use Wqa\NovaExtendFields\MultiSelectField;
use Wqa\NovaExtendFields\Fields\BelongsToMany;
use Wqa\NovaSortableTableResource\Concerns\SortsIndexEntries;
use App\Enums\AilmentType;

class TopUse extends Resource
{
    use SortsIndexEntries;

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\Usage';

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
    public static $title = 'name';

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
    public static $search = [
        'id',
        'name',
        //'description',
        'useable_name'
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
                Text::make('Name', 'name'),

                Textarea::make('Description', 'description'),

                UsesIcons::make('Uses Icons', 'uses_application'),
            ];
        }

        return [
            Text::make('Name', 'name'),

            Textarea::make('Description', 'description'),

            UsesIcons::make('Uses Icons', 'uses_application'),
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
            Text::make('Name'),

            Textarea::make('Description'),

            MultiSelectField::make('Ailments', 'ailments')
                ->withMeta(['selectedOptions' => $this->ailments->pluck('name', 'id')->mapToOptions()])
                ->options(\App\Ailment::where('ailment_type', AilmentType::Ailment)->orderBy('name')->pluck('name', 'id')->toArray()),

            MorphTo::make('Related Oil/Blend', 'useable')->types([
                Oil::class,
                Blend::class,
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
        return [
            new Filters\TopUseBySolution,
            //new Filters\TopUseByAilment,
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
        return [];
    }
}
