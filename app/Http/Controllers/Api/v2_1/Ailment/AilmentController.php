<?php

namespace App\Http\Controllers\Api\v2_1\Ailment;

use App\Ailment;
use App\Enums\AilmentType;
use Illuminate\Http\Request;
use App\Enums\ElementCacheKey;
use App\Enums\ApiCollectionResource;
use Illuminate\Support\Facades\Cache;
use Spatie\QueryBuilder\QueryBuilder;
use App\Http\Controllers\Api\v2_1\BaseQueryBuilderController;

 
/** 
*    @OA\SecurityScheme(
*        securityScheme="bearerAuth",
*        in="header",
*        type="http",
*        name="bearerAuth",
*        scheme="bearer",
*        bearerFormat="JWT",
*    ),
*/

class AilmentController extends BaseQueryBuilderController
{
    /**
     * @var Ailment
     */
    private $ailment;

    /**
     * @var Request
     */
    private $request;

    /**
     * All loadable relationships
     * @var array
     */
    private $allowedIncludes = [
        'body-systems',
        'remedies',
        'solutions.solutionable',
        'secondary-solutions.solutionable',
        'related-ailments',
        'related-body-systems',
        'symptoms',
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
     * AilmentController constructor.
     *
     * @param Ailment $ailment
     * @param Request $request
     */
    public function __construct(Ailment $ailment, Request $request)
    {
        $this->ailment = $ailment;
        $this->request = $request;

        // set up our query builder
        parent::__construct($ailment, $request, $this->allowedIncludes);
    }
     /** @OA\Get(
     *         path="/{lang}/v2.1/ailments",
     *         description="Fetch all ailment with given relationships",
     *         tags={"V2.1-api-auth"},
     *         @OA\Parameter(
     *             name="lang",
     *             in="path",
     *             description="language",
     *             required=true,
     *             explode=true,
     *             @OA\Schema(
     *                 default="en",
     *                 type="string",
     *                 enum={"en", "sp", "jp"},
     *             )
     *         ),
     *         @OA\Parameter(
     *              name="sort",
     *              in="query",
     *              description="Paramater to sort by.<br /><br />
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
     *             example="-name"
     *         )
     *     ),
     *     security={{ "bearerAuth": {} }},
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/AilmentCollectionResource2.1")
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
        if (! Cache::has(ElementCacheKey::Ailment)) {
            Cache::forever(
                ElementCacheKey::Ailment,
                $this->queryBuilder->allowedIncludes(
                    $this->allowedIncludes
                )->allowedSorts($this->allowedSorts)
                    ->defaultSort($this->defaultSort)
                    ->where('ailment_type', AilmentType::Ailment)
                    ->get()
            );
        }

        $data = Cache::get(ElementCacheKey::Ailment);

        return api_resource(ApiCollectionResource::Ailment)
            ->make($data);
    }
}
