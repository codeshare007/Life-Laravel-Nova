<?php

namespace App\Http\Controllers\Api\v2_1\Blend;

use App\Enums\ApiCollectionResource;
use App\Enums\ApiResource;
use App\Enums\ElementCacheKey;
use App\Http\Controllers\Api\v2_1\BaseQueryBuilderController;
use App\Blend;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Spatie\QueryBuilder\QueryBuilder;

class BlendController extends BaseQueryBuilderController
{
    /**
     * @var Blend
     */
    private $blend;

    /**
     * All loadable relationships
     * @var array
     */
    private $allowedIncludes = [
        'ingredients',
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
     * BlendController constructor.
     *
     * @param Blend $blend
     * @param Request $request
     */
    public function __construct(
        Blend $blend,
        Request $request
    ) {
        $this->blend = $blend;

        // set up our query builder
        parent::__construct($blend, $request, $this->allowedIncludes);
    }

    /**
     * @OA\Get(
     *     path="/{lang}/v2.1/blends",
     *     description="Fetch all blends with given relationships",
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
     *             example="name"
     *         )
     *     ),
     *     security={{ "bearerAuth": {} }},
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/BlendCollectionResource2.1")
     *         ),
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid query parameters",
     *         @OA\Schema(
     *              oneOf=@OA\Items(ref="#/components/schemas/InvalidSortQuery"),
     *                    @OA\Items(ref="#/components/schemas/InvalidIncludeQuery"),
     *         )
     *     )
     * )
     *
     * @return \Illuminate\Database\Eloquent\Collection|QueryBuilder[]
     */
    public function index()
    {
        if (! Cache::has(ElementCacheKey::Blend)) {
            Cache::forever(
                ElementCacheKey::Blend,
                $this->queryBuilder->allowedIncludes(
                    $this->allowedIncludes
                )->allowedSorts($this->allowedSorts)
                    ->defaultSort($this->defaultSort)
                    ->get()
            );
        }

        $data = Cache::get(ElementCacheKey::Blend);

        return api_resource(ApiCollectionResource::Blend)
            ->make($data);
    }
}
