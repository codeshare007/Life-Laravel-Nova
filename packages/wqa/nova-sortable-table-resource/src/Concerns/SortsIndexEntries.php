<?php

namespace Wqa\NovaSortableTableResource\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Laravel\Nova\Http\Requests\NovaRequest;

trait SortsIndexEntries
{
    protected $defaultSortBy = [
        'order_by' => 'id',
        'order_way' => 'asc'
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

        $resource = new static($request);
        $orderBy = ($request->relationshipType == 'belongsToMany' ? 'pivot_sort_order' : $resource->defaultSortBy['order_by']);
        $orderWay = $resource->defaultSortBy['order_way'];

        $query->when(empty($request->get('orderBy')), function (Builder $q) use ($orderBy, $orderWay) {
            $q->getQuery()->orders = [];

            return $q->orderBy($orderBy, $orderWay);
        });

        return $query;
    }
}
