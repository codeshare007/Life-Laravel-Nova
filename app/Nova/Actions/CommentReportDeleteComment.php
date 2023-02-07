<?php

namespace App\Nova\Actions;

use App\CommentReport;
use Illuminate\Bus\Queueable;
use Laravel\Nova\Actions\Action;
use Illuminate\Support\Collection;
use Laravel\Nova\Fields\ActionFields;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;

class CommentReportDeleteComment extends Action
{
    use InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The displayable name of the action.
     *
     * @var string
     */
    public $name = 'Delete Comment';

    /**
     * Perform the action on the given models.
     *
     * @param  \Laravel\Nova\Fields\ActionFields  $fields
     * @param  \Illuminate\Support\Collection  $models
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        foreach ($models as $commentReport) {
            /** @var CommentReport $commentReport */
            $commentReport->moderateDelete();
        }

        return Action::message('Comment(s) deleted.');
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields()
    {
        return [];
    }
}
