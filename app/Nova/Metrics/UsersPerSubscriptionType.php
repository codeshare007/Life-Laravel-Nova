<?php

namespace App\Nova\Metrics;

use App\User;
use Illuminate\Http\Request;
use App\Enums\SubscriptionType;
use Laravel\Nova\Metrics\Partition;
use Laravel\Nova\Http\Requests\NovaRequest;

class UsersPerSubscriptionType extends Partition
{
    /**
     * Calculate the value of the metric.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function calculate(Request $request)
    {
        return $this->result([
            'Legacy' => User::whereSubscriptionType(SubscriptionType::TheEssentialLifeMembershipLegacy)->count(),
            'Annual' => User::whereSubscriptionType(SubscriptionType::TheEssentialLifeMembership12Month)->count(),
            'Active Trial' => User::onActiveTrial()->count(),
            'Inactive Trial' => User::onInactiveTrial()->count(),
            'Not yet selected' => User::whereSubscriptionType(null)->count(),
        ]);
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
        return 'users-per-subscription-type';
    }
}
