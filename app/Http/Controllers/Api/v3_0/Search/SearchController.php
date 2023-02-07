<?php

namespace App\Http\Controllers\Api\v3_0\Search;

use App\Tag;
use App\Recipe;
use App\Remedy;
use App\Ailment;
use App\Enums\TagType;
use App\Enums\SearchModels;
use App\Enums\UserLanguage;
use Illuminate\Http\Request;
use App\Enums\RegionableModels;
use Illuminate\Support\Collection;
use App\Http\Controllers\Controller;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\Eloquent\Model;
use App\Http\Resources\v3_0\SearchResultResource;

class SearchController extends Controller
{
    protected $includeUgc;
    protected $query;

    protected $ugcModels = [
        Recipe::class,
        Remedy::class,
    ];

    /**
     * @OA\Get(
     *     path="/{lang}/v3.0/search",
     *     tags={"V3.0-api-auth"},
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
     *     operationId="index",
     *     security={{ "bearerAuth": {} }},
     *     @OA\RequestBody(
     *         description="Input data format",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                  required={"search"},
     *                  @OA\Property(
     *                      property="search",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      property="ugc",
     *                      type="boolean"
     *                  ),
     *             )
     *         )
     *     ),
     *     security={{ "bearerAuth": {} }},
     *     @OA\Response(
     *          response=200,
     *          description="OK",
     *          @OA\JsonContent(
     *              type="array",
     *              @OA\Items(ref="#/components/schemas/SearchResultResource3.0")
     *          ),
     *     ),
     *     @OA\Response(response=401, description="Unauthorized"),
     * )
     */
    public function index(Request $request)
    {
        $this->includeUgc = (bool) $request->get('ugc', false);
        $this->query = $request->search;

        return SearchResultResource::Collection($this->results());
    }

    protected function results(): Collection
    {
        if (! $this->query || mb_strlen($this->query) < $this->minQueryLength()) {
            return collect();
        }

        return collect(SearchModels::toArray())
            ->map(function ($modelName) {
                return $this->resultsForModel(new $modelName);
            })
            ->flatten(1);
    }

    protected function resultsForModel(Model $model): Collection
    {
        /** @var Builder */
        $queryBuilder = $model->where($model->getTable() . '.name', 'like', '%' . $this->query . '%');

        if (! $this->includeUgc && $this->isUgcModel($model)) {
            $queryBuilder->where('user_id', 0);
        }

        if ($model instanceof Tag) {
            $queryBuilder->filterByType(TagType::Property);
        }

        if ($this->hasRegionalName($model) && $this->userHasRegion()) {
            $queryBuilder->unionAll($this->queryByRegionalName($model));
        }

        return $queryBuilder
            ->get($this->getColumns($model))
            ->load($this->includes($model));
    }

    protected function isUgcModel(Model $model): Bool
    {
        return in_array(get_class($model), $this->ugcModels);
    }

    protected function includes(Model $model): array
    {
        if ($model instanceof Ailment) {
            return [
                'bodySystems',
            ];
        }

        if ($model instanceof Recipe) {
            return [
                'categories',
            ];
        }

        if ($model instanceof Remedy) {
            return [
                'bodySystems',
                'ailment',
            ];
        }

        return [];
    }

    protected function hasRegionalName(Model $model): Bool
    {
        return in_array(get_class($model), RegionableModels::getValues());
    }

    protected function userHasRegion(): Bool
    {
        $user = request()->user();

        return $user->region_id ? true : false;
    }

    protected function queryByRegionalName(Model $model): Builder
    {
        return $model->byRegionableName(
            $this->query,
            request()->user()->region_id
        );
    }

    protected function getColumns(Model $model): array
    {
        $columns = ['id', 'uuid', 'name'];

        if ($this->isUgcModel($model)) {
            $columns[] = 'user_id';
        }

        if ($model instanceof Ailment) {
            $columns[] = 'ailment_type';
        }

        return $columns;
    }

    protected function minQueryLength(): int
    {
        if (request()->user()->language->is(UserLanguage::Japanese)) {
            return 2;
        }

        return 3;
    }
}
