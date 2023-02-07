<?php

namespace Wqa\NovaExtendResources\Http\Controllers\Nova;

use Illuminate\Routing\Controller;
use Laravel\Nova\Http\Requests\ResourceIndexRequest;

class ResourceCountController extends Controller
{
    /**
     * Get the resource count for a given query.
     *
     * @param  \Laravel\Nova\Http\Requests\ResourceIndexRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function show(ResourceIndexRequest $request)
    {
        return response()->json(['count' => $request->toQuery()->count()]);
    }
}
