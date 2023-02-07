<?php

namespace App\Nova\Actions;

use Illuminate\Support\Carbon;

class EnableBypassSubscriptionReceiptValidation30Days extends EnableBypassSubscriptionReceiptValidation
{
    public $name = 'Override subscription expiry (30 days)';

    protected function expiry(): Carbon
    {
        return now()->addDays(30);
    }
}
