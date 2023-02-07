<?php

namespace App\Http\Controllers\Api\v2_1;

use App\Tag;
use App\Ailment;
use App\Element;
use Carbon\Carbon;
use App\Enums\TagType;
use App\Enums\ApiVersion;
use App\Enums\AilmentType;
use App\Enums\ElementType;
use App\Enums\MetaElements;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use App\Http\Controllers\Controller;
use Spatie\QueryBuilder\QueryBuilder;
use Juampi92\APIResources\APIResource;

class ElementController extends Controller
{
    /**
     * @var QueryBuilder
     */
    public $queryBuilder;

    /**
     * @var Element
     */
    private $element;

    /**
     * ElementController constructor.
     * @param Element $element
     */
    public function __construct(Element $element)
    {
        $this->element = $element;
    }

    /**
     * @OA\Get(
     *     path="/{lang}/v2.1/elements",
     *     description="Fetch the individual element using the global UUID",
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
     *         name="uuid",
     *         in="path",
     *         description="Element UUID",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *             example="abcd-1234-456h-343434"
     *         )
     *     ),
     *     security={{ "bearerAuth": {} }},
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\Schema(
     *              oneOf=@OA\Items(ref="#/components/schemas/OilResource2.1"),
     *                    @OA\Items(ref="#/components/schemas/AilmentResource2.1"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Model missing"
     *     )
     * )
     *
     * @param Request $request
     * @param $lang
     * @param $uuid
     * @return APIResource
     */
    public function show(Request $request, $lang, $uuid)
    {
        $elementCollection = $this->element
            ->with('elementDetails')
            // we use where here as opposed to find
            // as we need access to the loadMorph
            ->where('id', $uuid)
            ->get()
            ->loadMorph(
                'elementDetails',
                $this->buildElementEagerLoad()
            );

        $element = $elementCollection->first();
        if (!$element || !$element->elementDetails) {
            return response()->json([
                'message' => 'Resource couldn\'t be found'
            ], 404);
        }

        $resourceClass = $element->elementDetails->getApiResource(ApiVersion::v2_1());

        // get resource based on type
        return api_resource($resourceClass)
            ->make($element->elementDetails);
    }

    /**
     * Configure all element models for inclusion
     * @return array
     */
    private function buildElementEagerLoad()
    {
        return collect(ElementType::getValues())
            ->flatMap(function ($elementType) {
                return [
                    $elementType => (new $elementType)::getDefaultIncludes(ApiVersion::v2_1())
                ];
            })
            ->toArray();
    }

    /**
     * @OA\Get(
     *     path="/{lang}/v2.1/latest-updates",
     *     description="Fetch latest updates UUIDs",
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
     *         type="array"
     *     )
     * )
     */
    public function getLatestUpdates(Request $request)
    {
        $date = $request->get('modifiedSince', Carbon::now()->toDateTimeString());

        // @todo User whereHasMorph once updated to 5.8 or above

//        $elementCollection = $this->element
//            ->whereHasMorph('elementDetails', function ($query) use ($date) {
//                $query->where('updated_at', '>=', $date);
//            })
//            ->get(['id']);

        $elementCollection = collect([]);
        foreach (ElementType::toArray() as $element) {
            $includes = (new $element)::getDefaultIncludes(ApiVersion::v2_1());
            $class = (new $element);
            $elementCollection = $elementCollection->push(
                $class->with($includes)
                    ->where('updated_at', '>=', $date)
                    ->get()
                    ->flatMap(function ($element) use ($includes) {
                        $uuids = [$element->uuid];
                        foreach ($includes as $include) {
                            if ($element->{$include}) {
                                $relationship = $element->{$include};
                                if (! $relationship instanceof Collection) {
                                    $relationship = collect($element->{$include});
                                }
                                if ($relationshipUUIDs = $relationship->pluck('uuid')
                                        ->filter()
                                        ->all()) {
                                    $uuids = array_merge($uuids, $relationshipUUIDs);
                                }
                            }
                        }
                        return $uuids;
                    })->values()
            );
        }

        return response()->json([
            'data' => $elementCollection->flatten()->values()
        ]);
    }

    /**
     * REMOVE AFTER STAGING UPDATED TO USE LANG
     *
     *
     * @param $uuid
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Resources\Json\Resource
     */
    public function tempShow($uuid)
    {
        $elementCollection = $this->element
            ->with('elementDetails')
            // we use where here as opposed to find
            // as we need access to the loadMorph
            ->where('id', $uuid)
            ->get()
            ->loadMorph(
                'elementDetails',
                $this->buildElementEagerLoad()
            );

        $element = $elementCollection->first();
        if (!$element) {
            return response()->json([
                'message' => 'Resource couldn\'t be found'
            ], 404);
        }

        $resourceClass = $element->elementDetails->getApiResource(ApiVersion::v2_1());

        // get resource based on type
        return api_resource($resourceClass)
            ->make($element->elementDetails);
    }

    /**
     * @OA\Get(
     *     path="/{lang}/v2.1/meta",
     *     description="Fetch all counts",
     *     tags={"V2.1-api-token"},
     *     @OA\Parameter(
     *         name="lang",
     *         in="path",
     *         required=true,
     *         explode=true,
     *         @OA\Schema(
     *             default="en",
     *             type="string",
     *             enum={"en", "sp", "jp"},
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                  format="application/json",
     *                  property="data",
     *                  @OA\JsonContent(
     *                      type="object",
     *                      @OA\Property(
     *                          format="application/json",
     *                          property="total",
     *                          @OA\JsonContent(
     *                              type="object",
     *                              @OA\Property(property="Ailment", type="integer"),
     *                              @OA\Property(property="Symptom", type="integer"),
     *                              @OA\Property(property="Blend", type="integer"),
     *                              @OA\Property(property="BodySystem", type="integer"),
     *                              @OA\Property(property="Category", type="integer"),
     *                              @OA\Property(property="Oil", type="integer"),
     *                              @OA\Property(property="Recipe", type="integer"),
     *                              @OA\Property(property="Remedy", type="integer"),
     *                              @OA\Property(property="Supplement", type="integer"),
     *                              @OA\Property(property="Constituent", type="integer"),
     *                              @OA\Property(property="Property", type="integer"),
     *                              @OA\Property(property="Solution", type="integer"),  
     *                          )
     *                      )
     *                  )
     *             )
     *         ),
     *     ),
     *     security={{ "bearerAuth": {} }},
     *     @OA\Response(
     *         response=400,
     *         description="Invalid query parameters",
     *         @OA\Schema(
     *              oneOf=@OA\Items(ref="#/components/schemas/InvalidSortQuery"),
     *                    @OA\Items(ref="#/components/schemas/InvalidIncludeQuery"),
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthorized"),
     * )
     *
     * Fetch count of each element type
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function meta(Request $request)
    {
        $meta = [];
        foreach (MetaElements::getValues() as $modelType) {
            $enum = MetaElements::getInstance($modelType);
            $this->queryBuilder = QueryBuilder::for($enum->value, $request);
            if ($modelType == Ailment::class) {
                $ailments = $this->queryBuilder
                    ->get();
                $meta['Ailment'] = $ailments->where('ailment_type', AilmentType::Ailment)
                    ->count();
                $meta['Symptom'] = $this->queryBuilder->where('ailment_type', AilmentType::Symptom)
                    ->count();
            } elseif ($modelType == Tag::class) {
                $tags = $this->queryBuilder
                    ->get();
                $meta['Constituent'] = $tags->where('type', TagType::Constituent)
                    ->count();
                $meta['Property'] = $this->queryBuilder->where('type', TagType::Property)
                    ->count();
            } else {
                $meta[$enum->key] = $this->queryBuilder->get()->count();
            }
        }

        $meta['Solution'] = $meta['Oil'] + $meta['Blend'] + $meta['Supplement'];

        return response()->json(['data' => ['totals' => $meta]]);
    }
}
