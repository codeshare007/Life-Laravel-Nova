<?php

namespace App\Nova\Lenses;

use Illuminate\Http\Request;
use Laravel\Nova\Http\Requests\LensRequest;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Lenses\Lens as NovaLens;

abstract class ResourceExtensionLens extends NovaLens
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
            $request->resource()::indexQuery($request, $query)
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
        return $request->newResourceWith($this->resource)->fields($request);
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
        return $request->newResourceWith($this->resource)->filters($request);
    }

    /**
     * Get the fields that are available for the given request.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return \Illuminate\Support\Collection
     */
    protected function availableFields(NovaRequest $request)
    {
        return parent::availableFields($request)
            ->whereNotIn('component', ['belongs-to-many-field', 'has-many-field'])
            ->where('showOnIndex', true);
    }
}