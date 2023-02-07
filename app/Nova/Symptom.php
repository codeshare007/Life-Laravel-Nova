<?php

namespace App\Nova;

use Wqa\LinkField\Link;
use App\Enums\AilmentType;
use Illuminate\Http\Request;
use Wqa\DeepLinkTool\DeepLinkTool;
use Wqa\NovaExtendFields\Fields\Text;
use Wqa\NovaExtendFields\Fields\Select;
use Wqa\NovaExtendFields\Fields\HasMany;
use Wqa\NovaExtendFields\Fields\Textarea;
use Wqa\NovaExtendFields\MultiSelectField;
use Laravel\Nova\Http\Requests\NovaRequest;

class Symptom extends Ailment
{
    /**
     * Build an "index" query for the given resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function indexQuery(NovaRequest $request, $query)
    {
        $query->where('ailment_type', AilmentType::Symptom);

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
            Link::make('Name')->href('/admin/resources/symptoms/' . $this->id)->sortable(),

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
                    'value' => AilmentType::Symptom,
                ]),

            Textarea::make('Short Description'),

            MultiSelectField::make('Body Systems', 'bodySystems')
                ->withMeta(['selectedOptions' => $this->bodySystems->pluck('name', 'id')->mapToOptions()])
                ->options(\App\BodySystem::orderBy('name')->pluck('name', 'id')->toArray()),

            MultiSelectField::make('Related Body Systems', 'relatedBodySystems')
                ->withMeta(['selectedOptions' => $this->relatedBodySystems->pluck('name', 'id')->mapToOptions()])
                ->options(\App\BodySystem::orderBy('name')->pluck('name', 'id')->toArray()),

            MultiSelectField::make('Ailments', 'symptomAilments')
                ->withMeta(['selectedOptions' => $this->symptomAilments->pluck('name', 'id')->mapToOptions()])
                ->options(\App\Ailment::where('ailment_type', AilmentType::Ailment)->orderBy('name')->pluck('name', 'id')->toArray()),

            HasMany::make('Recommended Solutions', 'secondarySolutions', AilmentSecondarySolution::class),
        ];
    }

    public function tools()
    {
        return [
            DeepLinkTool::make()->forResource('Symptom', $this->uuid ?? ''),
        ];
    }
}
