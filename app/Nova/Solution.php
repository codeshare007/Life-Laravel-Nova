<?php

namespace App\Nova;

use App\Usage;
use R64\NovaFields\Row;
use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use App\EffectiveSolutionUsage;
use Laravel\Nova\Fields\Boolean;
use App\Enums\UseApplicationType;
use Illuminate\Support\Facades\DB;
use Laravel\Nova\Fields\MorphMany;
use Laravel\Nova\Fields\MorphToMany;
use Wqa\NovaExtendFields\Fields\Text;
use Laravel\Nova\Fields\BelongsToMany;
use Wqa\NovaExtendFields\Fields\Select;
use Wqa\NovaExtendFields\Fields\MorphTo;
use Wqa\NovaExtendFields\Fields\Textarea;
use Wqa\NovaExtendFields\Fields\BelongsTo;
use Laravel\Nova\Http\Requests\NovaRequest;
use Wqa\NovaSortableTableResource\Concerns\SortsIndexEntries;

class Solution extends Resource
{
    // use SortsIndexEntries;

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
    public static $model = 'App\Solution';

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
     * Return the fields for left column
     *
     * @return array
     */
    public function leftColumnFields()
    {
        return [
            MorphTo::make('Related Oil/Blend/Supplement', 'solutionable')->types([
                Oil::class,
                Blend::class,
                Supplement::class,
            ]),

            Textarea::make('Description'),

            BelongsToMany::make('Body Systems'),
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
