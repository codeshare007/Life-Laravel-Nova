<?php

namespace App\Services;

use App\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;

class PaginationAlphabeticalMetaData
{

    /**
     * Attach a count of collection data grouped by name
     *
     * @param Request $request
     * @param Builder
     * @return array
     */
    public static function configureMetaData(Request $request, $query)
    {
        $additionalMeta = [];
        $alphabet = collect(range('A', 'Z'));
        if (
            is_subclass_of($query, \Illuminate\Database\Eloquent\Model::class) ||
            $query instanceof Builder ||
            $query instanceof EloquentBuilder
        ) {
            $query = $query->get();
        }

        $dataByName = $query->groupBy(function ($item) {
            return $item['name'][0];
        })->all();

        $additionalMeta['alphabetical_data'] = $alphabet->map(function ($letter) use ($dataByName) {
            if (!isset($dataByName[$letter])) {
                return 0;
            }
            return count($dataByName[$letter]);
        })->all();

        return $additionalMeta;
    }
}
