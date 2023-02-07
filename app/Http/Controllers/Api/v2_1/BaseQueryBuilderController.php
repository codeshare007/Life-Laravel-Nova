<?php

namespace App\Http\Controllers\Api\v2_1;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

/**
 * @OA\Info(
 *     version="2.1.0",
 *     title="Essential life API",
 *     description="REST API consumed by Essential Life IOS and Android app",
 *     @OA\Contact(
 *         email="ibnaalberunyibrahim@gmail.com"
 *     ),
 * )
 */
class BaseQueryBuilderController extends Controller
{
    /**
     * @var QueryBuilder
     */
    public $queryBuilder;

    public function __construct(Model $modelType, Request $request, $defaultIncludes = [])
    {
        // set all includes by default. We can't use $request->merge() here becuse
        // the content type is JSON and it attaches it as json params and
        // the query builder isn't able to see them
        $request->query->add(['include' => collect($defaultIncludes)->implode(',')]);

        $this->queryBuilder = QueryBuilder::for(get_class($modelType), $request);
    }
}
