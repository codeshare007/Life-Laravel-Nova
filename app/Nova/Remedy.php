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
use Wqa\NovaSortableTableResource\Concerns\SortsIndexEntries;

class Remedy extends Resource
{
    use SortsIndexEntries;

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\Remedy';

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
            ];
        }

        return [
            Link::make('Name')->href('/admin/resources/remedies/' . $this->id)->sortable(),

            Textarea::make('Method', 'body'),

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
    public function leftColumnFields()
    {
        return [
            Text::make('Name'),

            Textarea::make('Method', 'body'),

            MultiSelectField::make('Related Remedies', 'related_remedies')
                ->withMeta(['selectedOptions' => $this->relatedRemedies->pluck('name', 'id')->mapToOptions()])
                ->options(\App\Remedy::orderBy('name')->pluck('name', 'id')->toArray())
                ->excludeOnUpdate()
                ->onlyOnForms(),

            // BelongsTo::make('Ailment'),

            HasMany::make('Ingredients', 'remedyIngredients', RemedyIngredient::class),
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
            DeepLinkTool::make()->forResource('Remedy', $this->uuid ?? ''),
        ];
    }
}
