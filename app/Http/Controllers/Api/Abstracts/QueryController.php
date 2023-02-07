<?php

namespace App\Http\Controllers\Api\Abstracts;

use Exception;
use App\Enums\CategoryType;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use BenSampo\Enum\Rules\EnumKey;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Validator;
use Juampi92\APIResources\Facades\APIResource;
use App\Exceptions\InvalidApiParameterException;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

abstract class QueryController extends Controller
{
    abstract static function getModelClass();

    static function getResourceClass()
    {
        return APIResource::resolveClassname('BasicResource');
    }

    static function getDefaultSortableColumn()
    {
        return static::getSortableColumns()[0];
    }

    static function getSortableColumns()
    {
        return ['name', 'total_views', 'recent_views'];
    }

    static function getWith()
    {
        return [];
    }

    static function getWithCount()
    {
        return [];
    }

    static function getSubModelRelationshipName()
    {
        return '';
    }

    static function getFilters()
    {
        return [];
    }

    /**
     * Display a listing of the resource.
     *
     * @param  string  $modelId
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, string $parentModelId = '')
    {
        $this->validateQuery($request);

        $builder = $this->getBuilder($parentModelId);
        $builder = $this->applyFilterCriteria($builder);
        $builder = $this->applySortCriteria($builder);
        $builder = $this->applyLimitCriteria($builder);
        $builder = $this->applyWithCriteria($builder);
        $builder = $this->applyWithCountCriteria($builder);

        $models = $builder->get();

        return static::getResourceClass()::collection($models);
    }

    private function validateQuery(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'sort_by' => [
                'sometimes',
                Rule::in(static::getSortableColumns())
            ],
            'limit' => 'sometimes|numeric',
            'filter_by_category_type' => [
                'sometimes',
                new EnumKey(CategoryType::class)
            ],
            'filter_by_featured' => 'sometimes|boolean',
        ]);

        if ($validation->fails()) {
            throw new InvalidApiParameterException($validation->getMessageBag()->getMessages());
        }
    }

    private function applyFilterCriteria(Builder $builder)
    {
        $filters = static::getFilters();

        if (in_array('filter_by_category_type', $filters) && request()->query('filter_by_category_type')) {
            $type = CategoryType::getValue(request()->query('filter_by_category_type'));
            return $builder->filterByType($type);
        }

        if (in_array('filter_by_featured', $filters) && request()->query('filter_by_featured') == true) {
            return $builder->filterByFeatured();
        }

        return $builder;
    }

    private function applySortCriteria(Builder $builder)
    {
        $defaultSortBy = static::getDefaultSortableColumn();
        $sortBy = request()->query('sort_by', $defaultSortBy);

        if ($sortBy === $defaultSortBy) {
            return $this->applySortCriteriaByName($builder, $sortBy);
        } else {
            $builder = $this->applySortCriteriaByName($builder, $sortBy);
            return $this->applySortCriteriaByName($builder, $defaultSortBy); // Always apply the default as a secondary sort by
        }

    }

    private function applySortCriteriaByName(Builder $builder, string $sortBy)
    {
        if ($sortBy === 'total_views') {
            return $builder->orderByTotalViews();
        } else if ($sortBy === 'recent_views') {
            return $builder->orderByRecentViews();
        } else if ($sortBy === 'latest') {
            return $builder->orderByMostRecent();
        } else if ($sortBy === 'name') {
            return $builder->orderBy('name');
        } else if ($sortBy === 'total_likes') {
            return $builder->orderByTotalLikes();
        }
    }

    private function applyLimitCriteria(Builder $builder)
    {
        $limit = request()->query('limit', 100000);

        return $builder->limit($limit);
    }

    private function applyWithCriteria(Builder $builder)
    {
        $with = static::getWith();
        return $builder->with($with);
    }

    private function applyWithCountCriteria(Builder $builder)
    {
        $withCount = static::getWithCount();
        return $builder->withCount($withCount);
    }

    private function getBuilder($parentModelId)
    {
        $model = resolve(static::getModelClass());

        if (! $parentModelId) {
            return $model::query();
        }

        $subModelRelationshipName = static::getSubModelRelationshipName();

        if (! $subModelRelationshipName) {
            throw new Exception('You need to set a value for the getSubModelRelationshipName() method in order to query a sub model.');
        }

        $relationship = $model::findOrFail($parentModelId)->$subModelRelationshipName();
        $query = $relationship->getQuery();

        if ($relationship instanceof BelongsToMany)
        {
            $pivotColumns = $relationship->getPivotColumns();
            if (count($pivotColumns)) {
                $query->addSelect($this->aliasedPivotColumns($pivotColumns));
            }
        }

        return $query;
    }

    private function aliasedPivotColumns($pivotColumns)
    {
        return collect($pivotColumns)->map(function ($column) {
            return $column.' as pivot_'.$column;
        })->unique()->all();
    }
}
