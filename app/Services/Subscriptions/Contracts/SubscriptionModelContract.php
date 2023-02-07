<?php

namespace App\Services\Subscriptions\Contracts;

use App\Enums\Platform;
use Illuminate\Support\Carbon;
use App\Enums\SubscriptionType;

/**
 * @property object $rawData
 * @property Platform $platform
 * @property SubscriptionType $type
 * @property Carbon $expiration
 * @property bool $isActive
 */
interface SubscriptionModelContract
{
    public function applyReceiptData(?object $receiptData): SubscriptionModelContract;

    public function applyBypass(Carbon $expiryDate = null): SubscriptionModelContract;
}
