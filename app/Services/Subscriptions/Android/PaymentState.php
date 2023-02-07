<?php

namespace App\Services\Subscriptions\Android;

class PaymentState
{
    public const PAYMENT_RECEIVED = 1;
    public const FREE_TRIAL = 2;
    public const PENDING_DEFERRED_UPGRADE_OR_DOWNGRADE = 3;
}