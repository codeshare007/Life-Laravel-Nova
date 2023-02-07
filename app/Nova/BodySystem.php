<?php

namespace App\Nova;

use App\Tag;
use App\Enums\TagType;
use Wqa\LinkField\Link;
use Illuminate\Http\Request;
use App\Nova\Actions\ForceUpdate;
use Wqa\DeepLinkTool\DeepLinkTool;
use Wqa\NovaExtendFields\Fields\Text;
use Wqa\NovaExtendFields\Fields\Image;
use Wqa\NovaExtendFields\Fields\Textarea;
use Wqa\NovaExtendFields\MultiSelectField;
use Wqa\NovaExtendFields\Fields\BelongsToMany;
use Wqa\NovaSortableTableResource\Concerns\SortsIndexEntries;

class BodySystem extends Resource
{
    use SortsIndexEntries;

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\BodySystem';

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

            Link::make('Name')->href('/admin/resources/body-systems/' . $this->id)->sortable(),

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
            Text::make('Name')->hideFromIndex(),

            Textarea::make('Short Description')->hideFromIndex(),

            Textarea::make('Usage Tip')->hideFromIndex(),

            MultiSelectField::make('Remedies', 'remedies')
                ->withMeta(['selectedOptions' => $this->remedies->pluck('name', 'id')->mapToOptions()])
                ->options(\App\Remedy::orderBy('name')->pluck('name', 'id')->toArray()),

            MultiSelectField::make('Ailments & Symptoms', 'ailmentsAndSymptoms')
                ->withMeta(['selectedOptions' => $this->ailmentsAndSymptoms->pluck('name', 'id')->mapToOptions()])
                ->options(\App\Ailment::orderBy('name')->pluck('name', 'id')->toArray()),

            MultiSelectField::make('Associated Properties', 'properties')
                ->withMeta(['selectedOptions' => $this->properties->pluck('name', 'id')->mapToOptions()])
                ->options(Tag::where('type', TagType::Property)->orderBy('name')->pluck('name', 'id')->toArray()),

            BelongsToMany::make('Single Oil Solutions', 'oils', Solution::class),

            BelongsToMany::make('Blended Solutions', 'blends', Solution::class),

            BelongsToMany::make('Supplements', 'supplements', Solution::class),
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
            DeepLinkTool::make()->forResource('BodySystem', $this->uuid ?? ''),
        ];
    }
}
