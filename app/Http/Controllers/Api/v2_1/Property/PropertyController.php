<?php

namespace App\Http\Controllers\Api\v2_1\Property;

use App\Enums\ApiCollectionResource;
use App\Enums\ApiResource;
use App\Enums\ElementCacheKey;
use App\Enums\TagType;
use App\Http\Controllers\Api\v2_1\BaseQueryBuilderController;
use App\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\Sort;

class PropertyController extends BaseQueryBuilderController
{
    /**
     * @var Tag
     */
    private $property;

    /**
     * All loadable relationships
     * @var array
     */
    private $allowedIncludes = [
        'oils'
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
    private $allowedSorts = ['name'];

    /**
     * PropertyController constructor.
     *
     * @param Tag $property
     */
    public function __construct(
        Tag $property,
        Request $request
    ) {
        $this->property = $property;

        // name is in the loaded oils relationship so declare an alias here
        $this->allowedSorts = Sort::field('name', 'oils');

        // set up our query builder
        parent::__construct($property, $request, $this->allowedIncludes);
    }

    /**
     * @OA\Get(
     *     path="/{lang}/v2.1/properties",
     *     description="Fetch all properties with given relationships",
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
                            </ul>",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *             @OA\Items(
     *                 type="string",
     *                 enum="{'name'}"
     *             )
     *         )
     *     ),
     *     security={{ "bearerAuth": {} }},
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/PropertyTagCollectionResource2.1")
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
        if (! Cache::has(ElementCacheKey::Tag)) {
            Cache::forever(
                ElementCacheKey::Tag,
                $this->queryBuilder->allowedIncludes(
                    $this->allowedIncludes
                )->allowedSorts($this->allowedSorts)
                    ->defaultSort($this->defaultSort)
                    ->get()
            );
        }

        $data = Cache::get(ElementCacheKey::Tag);

        return api_resource(ApiCollectionResource::Tag)
            ->make($data);
    }
}
