<?php

namespace App\Nova\Actions;

use Illuminate\Support\Carbon;

class EnableBypassSubscriptionReceiptValidation7Days extends EnableBypassSubscriptionReceiptValidation
{
    public $name = 'Override subscription expiry (7 days)';

    protected function expiry(): Carbon
    {
        return now()->addDays(7);
    }
}
