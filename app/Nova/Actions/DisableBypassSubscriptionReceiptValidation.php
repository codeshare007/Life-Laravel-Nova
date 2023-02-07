<?php

namespace App\Nova\Actions;

use App\User;
use Illuminate\Bus\Queueable;
use Laravel\Nova\Actions\Action;
use Illuminate\Support\Collection;
use Laravel\Nova\Fields\ActionFields;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;

class DisableBypassSubscriptionReceiptValidation extends Action
{
    use InteractsWithQueue, Queueable, SerializesModels;

    public $name = 'Disable subscription override';

    public function handle(ActionFields $fields, Collection $models)
    {
        foreach ($models as $user) {
            /** @var User $user */
            $user->disableBypass();
        }

        return Action::message('Receipt bypass disabled.');
    }

    public function fields()
    {
        return [];
    }
}
