<?php

namespace App\Nova\Actions;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Carbon;
use Laravel\Nova\Actions\Action;
use Illuminate\Support\Collection;
use Laravel\Nova\Fields\ActionFields;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;

abstract class EnableBypassSubscriptionReceiptValidation extends Action
{
    use InteractsWithQueue, Queueable, SerializesModels;

    public $name = 'Override subscription expiry';

    public function handle(ActionFields $fields, Collection $models)
    {
        foreach ($models as $user) {
            /** @var User $user */
            $user->enableBypass($this->expiry());
        }

        return Action::message('Receipt bypass enabled.');
    }

    public function fields()
    {
        return [];
    }

    abstract protected function expiry(): Carbon;
}
