<?php

namespace App\Nova\Lenses;

use Illuminate\Http\Request;
use App\Enums\CommentReport\Status;
use Wqa\NovaExtendFields\Fields\Text;
use Laravel\Nova\Http\Requests\LensRequest;

class ResolvedCommentReports extends ResourceExtensionLens
{
    public $name = 'Resolved';

    /**
     * Get the query builder / paginator for the lens.
     *
     * @param  \Laravel\Nova\Http\Requests\LensRequest  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return mixed
     */
    public static function query(LensRequest $request, $query)
    {
        return $request->withOrdering($request->withFilters(
            $query->where('status', Status::Resolved)
        ));
    }

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return array_merge(parent::fields($request), [
            Text::make('Action Taken', function() {
                return $this->action_taken->description;
            }),
        ]);
    }

    /**
     * Get the URI key for the lens.
     *
     * @return string
     */
    public function uriKey()
    {
        return 'resolved-comment-reports';
    }
}
