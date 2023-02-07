<?php

namespace App\Services\Subscriptions\iOS;

use Illuminate\Support\Carbon;
use App\Enums\SubscriptionType;
use App\Enums\Platform;
use App\Services\Subscriptions\BaseSubscriptionModel;
use Exception;

class iOSSubscription extends BaseSubscriptionModel
{
    public function getPlatform(): Platform
    {
        return Platform::iOS();
    }

    public function getType(): SubscriptionType
    {
        if ($this->isLegacySubscription()) {
            return SubscriptionType::TheEssentialLifeMembershipLegacy();
        }

        if ($this->isAnnualSubscription()) {
            return SubscriptionType::TheEssentialLifeMembership12Month();
        }

        if ($this->isTrialSubscription()) {
            return SubscriptionType::TheEssentialLifeMembershipTrial();
        }

        return SubscriptionType::None();
    }

    public function getExpiration(): Carbon
    {
        if ($this->type->is(SubscriptionType::TheEssentialLifeMembershipLegacy)) {
            // Users who bought the app when it was < version 2 get an indefinite subscription
            return new Carbon("2038-01-19 03:14:07"); // 2038-01-19 03:14:07 is the max value for storing timestamps in MYSQL
        }

        if ($this->mostRecentReceiptInfo()) {
            return new Carbon($this->mostRecentReceiptInfo()->expires_date);
        }
        
        throw new Exception('Failed to get expriation date.');
    }

    protected function isAnnualSubscription(): bool
    {
        return
            $this->mostRecentReceiptInfo() &&
            $this->mostRecentReceiptInfo()->is_trial_period === 'false';
    }

    protected function isTrialSubscription(): bool
    {
        return 
            $this->mostRecentReceiptInfo() &&
            $this->mostRecentReceiptInfo()->is_trial_period === 'true';
    }

    protected function isLegacySubscription(): bool
    {
        return 
            property_exists($this->rawData, 'receipt') &&
            property_exists($this->rawData->receipt, 'original_application_version') &&
            floatval($this->rawData->receipt->original_application_version) < 34; // 34 is the build number, and not the application version number as suggested by the key name!
    }

    protected function receiptDataIsValid(): bool
    {
        if ($this->rawData === null) {
            return false;
        }

        if ($this->rawData->status !== 0) {
            return false;
        }

        return $this->isAnnualSubscription() || $this->isTrialSubscription() || $this->isLegacySubscription();
    }

    protected function mostRecentReceiptInfo(): ?object 
    {
        if (! property_exists($this->rawData, 'latest_receipt_info')) {
            return null;
        }

        if (count($this->rawData->latest_receipt_info) === 0) {
            return null;
        }

        return collect($this->rawData->latest_receipt_info)
            ->sortByDesc('expires_date_ms')
            ->first();
    }
}
