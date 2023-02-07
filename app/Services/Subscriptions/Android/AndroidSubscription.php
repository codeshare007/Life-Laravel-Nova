<?php

namespace App\Services\Subscriptions\Android;

use Illuminate\Support\Carbon;
use App\Enums\SubscriptionType;
use App\Enums\Platform;
use App\Services\Subscriptions\Android\PaymentState;
use App\Services\Subscriptions\BaseSubscriptionModel;
use Google_Service_AndroidPublisher_SubscriptionPurchase;

class AndroidSubscription extends BaseSubscriptionModel
{
    /** @var Google_Service_AndroidPublisher_SubscriptionPurchase|null */
    public $rawData;

    public function getPlatform(): Platform
    {
        return Platform::Android();
    }

    public function getType(): SubscriptionType
    {
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
        $expiryTimeSeconds = $this->rawData->expiryTimeMillis / 1000;
        
        return Carbon::createFromTimestamp($expiryTimeSeconds);
    }

    protected function receiptDataIsValid(): bool
    {
        if ($this->rawData === null) {
            return false;
        }

        if (! $this->rawData instanceof Google_Service_AndroidPublisher_SubscriptionPurchase) {
            return false;
        }

        return true;
    }

    protected function isAnnualSubscription(): bool
    {
        return in_array($this->rawData->paymentState, [PaymentState::PAYMENT_RECEIVED, PaymentState::PENDING_DEFERRED_UPGRADE_OR_DOWNGRADE]);
    }

    protected function isTrialSubscription(): bool
    {
        return $this->rawData->paymentState === PaymentState::FREE_TRIAL;
    }
}
