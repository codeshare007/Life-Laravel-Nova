<?php

namespace Wqa\NovaExtendResources\Http\Controllers\Nova;

use Illuminate\Routing\Controller;
use Laravel\Nova\Http\Requests\ResourceIndexRequest;

class ResourceIndexController extends Controller
{
    /**
     * List the resources for administration.
     *
     * @param  \Laravel\Nova\Http\Requests\ResourceIndexRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function handle(ResourceIndexRequest $request)
    {
        $paginator = $this->paginator(
            $request, $resource = $request->resource()
        );

        return response()->json([
            'label' => $resource::label(),
            'resources' => $paginator->getCollection()->mapInto($resource)->map->serializeForIndex($request),
            'prev_page_url' => $paginator->previousPageUrl(),
            'next_page_url' => $paginator->nextPageUrl(),
            'softDeletes' => $resource::softDeletes(),
        ]);
    }

    /**
     * Get the paginator instance for the index request.
     *
     * @param  \Laravel\Nova\Http\Requests\ResourceIndexRequest  $request
     * @param  string  $resource
     * @return \Illuminate\Pagination\Paginator
     */
    protected function paginator(ResourceIndexRequest $request, $resource)
    {
        return $request->toQuery()->simplePaginate(
            $request->viaRelationship()
                        ? $resource::$perPageViaRelationship
                        : ($request->perPage ?? 25)
        );
    }
}
