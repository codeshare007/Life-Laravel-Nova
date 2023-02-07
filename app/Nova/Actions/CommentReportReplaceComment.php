<?php

namespace App\Nova\Actions;

use App\CommentReport;
use Illuminate\Bus\Queueable;
use Laravel\Nova\Actions\Action;
use Illuminate\Support\Collection;
use Laravel\Nova\Fields\ActionFields;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Wqa\NovaExtendFields\Fields\Textarea;
use Illuminate\Contracts\Queue\ShouldQueue;

class CommentReportReplaceComment extends Action
{
    use InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The displayable name of the action.
     *
     * @var string
     */
    public $name = 'Replace Comment';

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
            $commentReport->moderateReplace($fields['replacement_text']);
        }

        return Action::message('Comment(s) replaced.');
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields()
    {
        return [
            Textarea::make('Replace comment text with', 'replacement_text')
                ->withMeta(['value' => 'This comment has been removed.']),
        ];
    }
}
