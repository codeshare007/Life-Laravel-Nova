<?php

namespace Wqa\NovaSortableTableResource\Http\Controllers;

use App\EffectiveSolutionUsage;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Laravel\Nova\Http\Requests\NovaRequest;

class ResourceUpdateController extends Controller
{
    public function handle(NovaRequest $request)
    {
        if (!$request->updatedModels) {
            return response('', 200);
        }

        $resource = $request->resource();
        
        foreach ($request->updatedModels as $modelId => $updatedModelData) {
            $model = $request->findModelQuery($modelId)->firstOrFail();

            $this->updateResource($resource, $model, $updatedModelData);
        }

        return response('', 200);
    }

    protected function updateResource($resource, $model, $updatedModelData)
    {
        return DB::transaction(function () use ($resource, $model, $updatedModelData) {
            [$model, $callbacks] = $resource::fillQuickEditFields($updatedModelData, $model);

            return tap(tap($model)->save(), function ($model) use ($callbacks) {
                collect($callbacks)->each->__invoke();
            });
        });
    }
}
