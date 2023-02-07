<?php

namespace Wqa\NovaExtendResources\Http\Controllers\Nova;

use Illuminate\Routing\Controller;
use App\Services\LanguageDatabaseService;
use Laravel\Nova\Http\Requests\CreateResourceRequest;

class ResourceStoreController extends Controller
{
    /**
     * Create a new resource.
     *
     * @param \Laravel\Nova\Http\Requests\CreateResourceRequest $request
     * @return \Illuminate\Http\Response
     */
    public function handle(CreateResourceRequest $request)
    {
        $resource = $request->resource();

        $resource::authorizeToCreate($request);

        $resource::validateForCreation($request);

        [$model, $callbacks] = $resource::fill(
            $request, $resource::newModel()
        );

        if ($request->viaRelationship()) {
            $request->findParentModelOrFail()
                ->{$request->viaRelationship}()
                ->save($model);
        } else {
            $model->save();
        }

        collect($callbacks)->each->__invoke();

        return $model;
    }
}
