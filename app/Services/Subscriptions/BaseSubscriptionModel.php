<?php

namespace App\Services\Subscriptions;

use App\Enums\Platform;
use Illuminate\Support\Carbon;
use App\Enums\SubscriptionType;
use App\Services\Subscriptions\Contracts\SubscriptionModelContract;

abstract class BaseSubscriptionModel implements SubscriptionModelContract
{
    /** @var object|null */
    public $rawData;

    /** @var Platform */
    public $platform;

    /** @var SubscriptionType */
    public $type;

    /** @var Carbon */
    public $expiration;

    /** @var bool */
    public $isActive;

    public function __construct()
    {
        $this->platform = $this->getPlatform();
        $this->type = SubscriptionType::None();
        $this->expiration = Carbon::createFromTimestamp(0)->addDay();
        $this->isActive = false;
    }

    public function applyReceiptData(?object $receiptData): SubscriptionModelContract
    {
        $this->rawData = $receiptData;

        if ($this->receiptDataIsValid()) {
            $this->platform = $this->getPlatform();
            $this->type = $this->getType();
            $this->expiration = $this->getExpiration();
            $this->isActive = $this->getIsActive();
        }

        return $this;
    }

    public function applyBypass(Carbon $expiryDate = null): SubscriptionModelContract
    {
        $this->expiration = $expiryDate ?? now()->addYear();
        $this->type = SubscriptionType::TheEssentialLifeMembership12Month();
        $this->isActive = $this->getIsActive();

        return $this;
    }

    protected function getIsActive(): bool
    {
        if ($this->type->is(SubscriptionType::None)) {
            return false;
        }

        return $this->expiration->isFuture();
    }

    abstract protected function getPlatform(): Platform;

    abstract protected function getType(): SubscriptionType;

    abstract protected function getExpiration(): Carbon;

    abstract protected function receiptDataIsValid(): bool;
}
