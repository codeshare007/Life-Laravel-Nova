<?php

namespace App\Nova\Filters;

use App\Blend;
use App\Oil;
use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;

class TopUseBySolution extends Filter
{
    /**
     * Apply the filter to the given query.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  mixed  $value
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function apply(Request $request, $query, $value)
    {
        $value = explode('_', $value);
        if (isset($value[1])) {
            $useableId = $value[0];
            $useableType = 'App\\'.$value[1];
            return $query->where('useable_type', $useableType)->where('useable_id', $useableId);
        }
    }

    /**
     * Get the filter's available options.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function options(Request $request)
    {
        $oils = Oil::pluck('id', 'name')->toArray();
        $blends = Blend::pluck('id', 'name')->toArray();
        ksort($oils);
        ksort($blends);

        return [
            'oil' => $oils,
            'blend' => $blends,
        ];
    }
}
