<?php

namespace App\Nova;

use Illuminate\Http\Request;
use App\Enums\CommentReport\Status;
use Wqa\NovaExtendFields\Fields\Text;
use Wqa\NovaExtendFields\Fields\DateTime;
use Wqa\NovaExtendFields\Fields\Textarea;
use App\Nova\Actions\CommentReportResolve;
use Wqa\NovaExtendFields\Fields\BelongsTo;
use App\Nova\Lenses\ResolvedCommentReports;
use Laravel\Nova\Http\Requests\NovaRequest;
use App\Nova\Actions\CommentReportDeleteComment;
use App\Nova\Actions\CommentReportReplaceComment;

class CommentReport extends Resource
{
    public static $displayInNavigation = false;

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\CommentReport';

    /**
     * The logical group associated with the resource.
     *
     * @var string
     */
    // public static $group = ['Comments'];

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'comment';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
    ];

    public static $with = [
        'reporter',
        'commenter',
    ];

    public static function authorizedToCreate(Request $request)
    {
        return false;
    }

    public function authorizedToDelete(Request $request)
    {
        return false;
    }

    public static function label()
    {
        return 'Reported Comments';
    }

    /**
     * Build an "index" query for the given resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function indexQuery(NovaRequest $request, $query)
    {
        return $query->where('status', Status::Open);
    }

    /**
     * Return the fields for the index view.
     *
     * @return array
     */
    public function fieldsForIndex()
    {
        return [
            DateTime::make('Reported at', 'created_at')->withMeta(['extraAttributes' => [
                'disabled' => true,
            ]]),

            Textarea::make('Reason')->withMeta(['extraAttributes' => [
                'disabled' => true,
            ]]),

            Textarea::make('Comment')->withMeta(['extraAttributes' => [
                'disabled' => true,
            ]]),

            Text::make('Status', function () {
                return $this->status->description;
            }),

            BelongsTo::make('Reported by', 'reporter', User::class)->searchable()->withMeta(['disabled' => true]),

            BelongsTo::make('Comment by', 'commenter', User::class)->searchable()->withMeta(['disabled' => true]),
        ];
    }

    /**
     * Return the fields for left column.
     *
     * @return array
     */
    protected function leftColumnFields()
    {
        return $this->fieldsForIndex();
    }

    /**
     * Return the fields for right column.
     *
     * @return array
     */
    protected function rightColumnFields()
    {
        return [
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
        return [
            new ResolvedCommentReports,
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
            new CommentReportDeleteComment,
            new CommentReportReplaceComment,
            new CommentReportResolve,
        ];
    }
}
