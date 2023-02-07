<?php

namespace Wqa\NovaSortableTableResource\Http\Controllers;

use App\Http\Controllers\Controller;
use Wqa\NovaSortableTableResource\Http\Requests\ReorderResourceRequest;
use Spatie\EloquentSortable\Sortable;

class ResourceSortableController extends Controller
{
    public function handle(ReorderResourceRequest $request)
    {
        $request->findResourceOrFail()->authorizeToUpdate($request);
        $positions = $request->get('positions', null);

        if (!is_array($positions)) {
            return response('', 500);
        }

        $currentItemPosition = array_search($request->resourceId, $positions);

        $model = $request->findModelQuery()->firstOrFail();
        if (!$model instanceof Sortable) {
            return response(sprintf('Model %s is not sortable', get_class($model)), 500);
        }

        $request->resourceName = camel_case($request->resourceName);
        $relatedModel = $model->{$request->resourceName} ?? null;

        if ($relatedModel) {
            foreach ($positions as $position => $id) {
                $model->{$request->resourceName}()->updateExistingPivot($id, ['sort_order' => $position]);
            }
        } else {
            get_class($model)::setNewOrder($positions, $currentItemPosition);
        }

        return response('', 200);
    }
}
