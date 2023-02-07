<?php

namespace App\Nova\Actions;

use Illuminate\Support\Carbon;

class EnableBypassSubscriptionReceiptValidation14Days extends EnableBypassSubscriptionReceiptValidation
{
    public $name = 'Override subscription expiry (14 days)';

    protected function expiry(): Carbon
    {
        return now()->addDays(14);
    }
}
