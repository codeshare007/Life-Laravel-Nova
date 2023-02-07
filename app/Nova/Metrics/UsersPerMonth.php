<?php

namespace App\Nova\Metrics;

use App\User;
use Illuminate\Http\Request;
use Laravel\Nova\Metrics\Trend;
use Laravel\Nova\Http\Requests\NovaRequest;

class UsersPerMonth extends Trend
{
    public $name = 'Total Users Per Month';

    /**
     * Calculate the value of the metric.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function calculate(Request $request)
    {
        return $this->countByMonths($request, User::class)
            ->showLatestValue()
            ->format('0,0');
    }

    /**
     * Get the ranges available for the metric.
     *
     * @return array
     */
    public function ranges()
    {
        return [
            12 => '12 Months',
            24 => '24 Months',
        ];
    }

    /**
     * Determine for how many minutes the metric should be cached.
     *
     * @return  \DateTimeInterface|\DateInterval|float|int
     */
    public function cacheFor()
    {
        return now()->addMinutes(5);
    }

    /**
     * Get the appropriate cache key for the metric.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return string
     */
    protected function getCacheKey(NovaRequest $request)
    {
        return parent::getCacheKey($request) . session()->get('selected_language');
    }

    /**
     * Get the URI key for the metric.
     *
     * @return string
     */
    public function uriKey()
    {
        return 'users-per-month';
    }
}
