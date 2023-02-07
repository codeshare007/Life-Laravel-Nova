<?php

namespace App\Nova;

use Wqa\LinkField\Link;
use Illuminate\Http\Request;
use App\Nova\Actions\RejectQuestion;
use App\Nova\Actions\ApproveQuestion;
use Wqa\NovaExtendFields\Fields\Text;
use App\Nova\Lenses\FeaturedQuestions;
use App\Nova\Actions\UnapproveQuestion;
use Wqa\NovaExtendFields\Fields\Textarea;
use App\Nova\Filters\QuestionStatusFilter;
use Wqa\NovaExtendFields\Fields\BelongsTo;
use Wqa\NovaExtendFields\Fields\BooleanSwitch;
use Wqa\NovaSortableTableResource\Concerns\SortsIndexEntries;

class Question extends Resource
{
    use SortsIndexEntries;

    public static $displayInNavigation = false;

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
    public static $model = 'App\Question';

    /**
     * The logical group associated with the resource.
     *
     * @var string
     */
    public static $group = ['Dashboard'];

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'title';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'title',
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
                Text::make('Category')->sortable(),

                Link::make('Title')->href('/admin/resources/questions/' . $this->id)->sortable(),

                Textarea::make('Description'),

                BooleanSwitch::make('Featured', 'is_featured')->sortable(),
            ];
        }

        return [
            Text::make('Category')->sortable(),

            Link::make('Title')->href('/admin/resources/questions/' . $this->id)->sortable(),

            Textarea::make('Description'),

            Text::make('Status', function () {
                return $this->status->description;
            }),

            BooleanSwitch::make('Featured', 'is_featured')->sortable(),

            BelongsTo::make('User')->sortable(),
        ];
    }

    /**
     * Return the fields for left column.
     *
     * @return array
     */
    protected function leftColumnFields()
    {
        return [
            Text::make('Category'),

            Text::make('Title'),

            Textarea::make('Description'),
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
            BooleanSwitch::make('Featured', 'is_featured'),

            BelongsTo::make('User')->searchable(),
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
            new QuestionStatusFilter(),
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
        return [
            new FeaturedQuestions,
        ];
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
            new ApproveQuestion,
            new UnapproveQuestion,
            new RejectQuestion,
        ];
    }
}
