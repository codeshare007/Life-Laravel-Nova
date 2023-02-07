<?php

namespace App\Nova;

use Wqa\LinkField\Link;
use App\Enums\AilmentType;
use Illuminate\Http\Request;
use App\Nova\Actions\ForceUpdate;
use Wqa\DeepLinkTool\DeepLinkTool;
use Wqa\NovaExtendFields\Fields\Text;
use Wqa\NovaExtendFields\Fields\Select;
use Wqa\NovaExtendFields\Fields\HasMany;
use Wqa\NovaExtendFields\Fields\Textarea;
use Wqa\NovaExtendFields\MultiSelectField;
use Laravel\Nova\Http\Requests\NovaRequest;
use App\SolutionGroup as SolutionGroupModel;

class Ailment extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\Ailment';

    /**
     * The logical group associated with the resource.
     *
     * @var string
     */
    public static $group = ['Body Systems'];

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
        'short_description',
    ];

    /**
     * Build an "index" query for the given resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function indexQuery(NovaRequest $request, $query)
    {
        $query->where('ailment_type', AilmentType::Ailment);

        $query->when(empty($request->get('orderBy')), function ($q) {
            $q->getQuery()->orders = [];

            return $q->orderBy('name', 'asc');
        });

        return $query;
    }

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
            Link::make('Name')->href('/admin/resources/ailments/' . $this->id)->sortable(),

            Textarea::make('Short Description'),
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
            Text::make('Name'),

            Select::make('Type', 'ailment_type')
                ->options(AilmentType::toSelectArray())
                ->displayUsingLabels()
                ->withMeta([
                    'value' => AilmentType::Ailment,
                ]),

            Textarea::make('Short Description'),

            MultiSelectField::make('Body Systems', 'bodySystems')
                ->withMeta(['selectedOptions' => $this->bodySystems->pluck('name', 'id')->mapToOptions()])
                ->options(\App\BodySystem::orderBy('name')->pluck('name', 'id')->toArray()),

            MultiSelectField::make('Related Body Systems', 'relatedBodySystems')
                ->withMeta(['selectedOptions' => $this->relatedBodySystems->pluck('name', 'id')->mapToOptions()])
                ->options(\App\BodySystem::orderBy('name')->pluck('name', 'id')->toArray()),

            MultiSelectField::make('Symptoms', 'symptoms')
                ->withMeta(['selectedOptions' => $this->symptoms->pluck('name', 'id')->mapToOptions()])
                ->options(\App\Ailment::where('ailment_type', AilmentType::Symptom)->orderBy('name')->pluck('name', 'id')->toArray()),

            // Dev note, this doesn't work - possibly because it's not the correct relationship type?
            // MultiSelectField::make('Remedies', 'remedies')
            //     ->withMeta(['selectedOptions' => $this->remedies->pluck('name', 'id')->mapToOptions()])
            //     ->options(\App\Remedy::orderBy('name')->pluck('name', 'id')->toArray()),

            HasMany::make('Effective Solutions', 'solutions', AilmentSolution::class),

            HasMany::make('Supporting Solutions', 'secondarySolutions', AilmentSecondarySolution::class),
        ];
    }

    public function tools()
    {
        return [
            DeepLinkTool::make()->forResource('Ailment', $this->uuid ?? ''),
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

    /**
     * Fill a new pivot model instance using the given request.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  \Illuminate\Database\Eloquent\Relations\Pivot  $pivot
     * @return array
     */
    public static function fillPivot(NovaRequest $request, $model, $pivot)
    {
        $solutionGroup = SolutionGroupModel::find($pivot->solution_id);
        $pivot->uses_application = '[{"name":"Aromatic","active":false,"position":999},{"name":"Topical","active":false,"position":999},{"name":"Internal","active":false,"position":999}]';
        $pivot->useable_id = $solutionGroup->useable_id ?? 0;
        $pivot->useable_type = $solutionGroup->useable_type ?? 0;

        return parent::fillPivot($request, $model, $pivot);
    }
}
