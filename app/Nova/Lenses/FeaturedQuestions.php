<?php

namespace App\Nova\Lenses;

use Laravel\Nova\Http\Requests\LensRequest;

class FeaturedQuestions extends ResourceExtensionLens
{
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
            $query->featured()
        ));
    }

    /**
     * Get the URI key for the lens.
     *
     * @return string
     */
    public function uriKey()
    {
        return 'featured-questions';
    }
}
