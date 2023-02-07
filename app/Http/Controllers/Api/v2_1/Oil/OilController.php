<?php

namespace App\Http\Controllers\Api\v2_1\Oil;

use App\Enums\ApiCollectionResource;
use App\Enums\ElementCacheKey;
use App\Http\Controllers\Api\v2_1\BaseQueryBuilderController;
use App\Oil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Spatie\QueryBuilder\QueryBuilder;

class OilController extends BaseQueryBuilderController
{
    /**
     * @var Oil
     */
    private $oil;

    /**
     * All loadable relationships
     * @var array
     */
    private $allowedIncludes = [
        'blends-with',
        'found-in-blends',
        'found-in',
        'properties',
        'constituents',
        'sourcing-methods',
        'safety-information',
        'usages.ailments',
    ];

    /**
     * Default sort attribute
     * @var string
     */
    private $defaultSort = 'name';

    /**
     * All sort attribute
     * @var array
     */
    private $allowedSorts = [
        'name',
        'views_count',
    ];

    /**
     * OilController constructor.
     *
     * @param Oil $oil
     * @param Request $request
     */
    public function __construct(
        Oil $oil,
        Request $request
    ) {
        $this->oil = $oil;

        // set up our query builder
        parent::__construct($oil, $request, $this->allowedIncludes);
    }

    /**
     * @OA\Get(
     *     path="/{lang}/v2.1/oils",
     *     description="Fetch all oils with given relationships",
     *     tags={"V2.1-api-auth"},
     *     @OA\Parameter(
     *         name="lang",
     *         in="path",
     *         description="language",
     *         required=true,
     *         explode=true,
     *         @OA\Schema(
     *             default="en",
     *             type="string",
     *             enum={"en", "sp", "jp"},
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="sort",
     *         in="query",
     *         description="Paramater to sort by.<br /><br />
                            To toggle ASC/DESC preceed the parameter with a '-'.<br /><br />
                            Allowed sort parameters:<br />
                            <ul>
                                <li>name</li>
                                <li>views_count</li>
                            </ul>",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *             @OA\Items(
     *                 type="string",
     *                 enum="{'name', 'views_count'}"
     *             ),
     *             example="name,views_count"
     *         )
     *     ),
     *     security={{ "bearerAuth": {} }},
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/OilCollectionResource2.1")
     *         ),
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid query parameters",
     *         @OA\Schema(
     *              oneOf=@OA\Items(ref="#/components/schemas/InvalidSortQuery"),
     *                    @OA\Items(ref="#/components/schemas/InvalidIncludeQuery"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated."
     *     )
     * )
     *
     * @return \Illuminate\Database\Eloquent\Collection|QueryBuilder[]
     */
    public function index()
    {
        if (! Cache::has(ElementCacheKey::Oil)) {
            Cache::forever(
                ElementCacheKey::Oil,
                $this->queryBuilder->allowedIncludes(
                    $this->allowedIncludes
                )->allowedSorts($this->allowedSorts)
                    ->defaultSort($this->defaultSort)
                    ->get()
            );
        }

        return api_resource(ApiCollectionResource::Oil)
            ->make(Cache::get(ElementCacheKey::Oil));
    }
}
