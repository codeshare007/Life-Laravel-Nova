<?php

namespace App\Http\Controllers\Api\v2_1\Solution;

use App\Oil;
use App\Blend;
use App\Solution;
use App\Supplement;
use App\Enums\ApiVersion;
use Illuminate\Http\Request;
use App\Enums\ElementCacheKey;
use App\Enums\ApiCollectionResource;
use Illuminate\Support\Facades\Cache;
use Spatie\QueryBuilder\QueryBuilder;
use App\Services\PaginationAlphabeticalMetaData;
use App\Http\Controllers\Api\v2_1\BaseQueryBuilderController;

class SolutionController extends BaseQueryBuilderController
{
    /**
     * @var Solution
     */
    private $solution;

    /**
     * @var Blend
     */
    private $blend;

    /**
     * @var Oil
     */
    private $oil;

    /**
     * @var Supplement
     */
    private $supplement;

    /**
     * SolutionController constructor.
     * @param Solution $solution
     * @param Blend $blend
     * @param Oil $oil
     * @param Supplement $supplement
     */
    public function __construct(
        Solution $solution,
        Blend $blend,
        Oil $oil,
        Supplement $supplement
    ) {
        $this->solution = $solution;
        $this->blend = $blend;
        $this->oil = $oil;
        $this->supplement = $supplement;
    }

    /**
     * @OA\Get(
     *     path="/{lang}/v2.1/solutions",
     *     description="Fetch all solutions with given relationships",
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
     *     security={{ "bearerAuth": {} }},
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/SolutionGroupResource2.1")
     *         ),
     *     )
     * )
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Collection|QueryBuilder[]
     */
    public function index(Request $request)
    {

        // We do have a solutions table that manages these relationships
        // however it doesn't appear that the data has been ported over
        // completely. I will leave this in for future ref, in the hope
        // we can pivot to using the polymoprhic relationship and make the
        // this end point more performant

//        $query = $this->solution->with('solutionable')
//            ->orderBy('name')
//            ->paginate(750);
//
//        $query
//            ->getCollection()
//            ->loadMorph('solutionable', [
//                Blend::class => Blend::getDefaultIncludes(ApiVersion::v2_1()),
//                Oil::class => Oil::getDefaultIncludes(ApiVersion::v2_1()),
//                Supplement::class => Supplement::getDefaultIncludes(ApiVersion::v2_1()),
//            ]);
//
//        return api_resource(ApiCollectionResource::SolutionGroup)
//            ->make($query);


        if (! Cache::has(ElementCacheKey::Solution)) {
            $blendQuery = $this->blend
                ->with(Blend::getDefaultIncludes(ApiVersion::v2_1()))
                ->orderBy('name')
                ->get();

            $blendResource = api_resource(ApiCollectionResource::Blend)
                ->make($blendQuery);

            $oilQuery = $this->oil
                ->with(Oil::getDefaultIncludes(ApiVersion::v2_1()))
                ->get();

            $oilResource = api_resource(ApiCollectionResource::Oil)
                ->make($oilQuery);

            $supplementQuery = $this->supplement
                ->with(Supplement::getDefaultIncludes(ApiVersion::v2_1()))
                ->get();

            $supplementResource = api_resource(ApiCollectionResource::Supplement)
                ->make($supplementQuery);

            $solutions = $blendResource->collection->merge($oilResource->collection);
            $solutions = $solutions->merge($supplementResource->collection);

            Cache::forever(
                ElementCacheKey::Solution,
                array_merge(
                    ['data'=> $solutions->flatten()],
                    PaginationAlphabeticalMetaData::configureMetaData(
                        $request,
                        $solutions
                    )
                )
            );
        }

        $data = Cache::get(ElementCacheKey::Solution);

        return response()->json($data);
    }
}
