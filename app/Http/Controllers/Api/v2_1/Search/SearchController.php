<?php

namespace App\Http\Controllers\Api\v2_1\Search;

use App\Tag;
use App\Recipe;
use App\Remedy;
use App\Enums\TagType;
use App\Enums\ApiVersion;
use App\Enums\SearchModels;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $includeUgc = $request->get('ugc', false);
        $ugcModels = [
            Recipe::class,
            Remedy::class,
        ];
        $results = [];
        foreach (SearchModels::getValues() as $model) {
            $modelClass = (new $model);

            // re-enable search after tntsearch get
            // back to you

            // $query = $modelClass->search($request->search)
            //  ->query(function ($builder) use ($modelClass) {
            //      $builder->with($modelClass::getDefaultIncludes(ApiVersion::v2_1()));
            //  });

            $query = $modelClass
                ->with($modelClass::getDefaultIncludes(ApiVersion::v2_1()))
                ->where('name', 'like', '%' . $request->search . '%');

            if (!$includeUgc && in_array($model, $ugcModels)) {
                $query->where('user_id', 0);
            }

            if ($modelClass instanceof Tag) {
                $query->filterByType(TagType::Property);
            }

            $results[] = $query->get();
        }

        $searchResults = collect($results)->flatMap(function ($res) {
            return array_map(function ($model) {
                return api_resource($model->getApiResource(ApiVersion::v2_1()))
                    ->make($model);
            }, $res->all());
        });

        return response()->json(['data' => $searchResults]);
    }
}
