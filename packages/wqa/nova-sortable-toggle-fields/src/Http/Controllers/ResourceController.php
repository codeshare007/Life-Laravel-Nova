<?php

namespace Wqa\NovaSortableToggleFields\Http\Controllers;

use App\Http\Controllers\Controller;
use Wqa\NovaSortableToggleFields\Http\Requests\ResourceRequest;

class ResourceController extends Controller
{
    public function handle(ResourceRequest $request)
    {
        $request->findResourceOrFail()->authorizeToUpdate($request);
        $model = $request->findModelQuery()->firstOrFail();

        return response('', 200);
    }
}
