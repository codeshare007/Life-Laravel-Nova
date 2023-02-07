<?php

namespace App\Nova\Actions;

use App\Events\RejectedUserGeneratedContent;
use Illuminate\Bus\Queueable;
use Laravel\Nova\Actions\Action;
use Illuminate\Support\Collection;
use Laravel\Nova\Fields\ActionFields;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Wqa\NovaExtendFields\Fields\Select;
use Wqa\NovaExtendFields\Fields\Textarea;
use App\UserGeneratedContent;

class RejectUserGeneratedContent extends Action
{
    use InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The displayable name of the action.
     *
     * @var string
     */
    public $name = 'Reject';

    private $reasonSubjects = [
        'Please upload another image, you may not have the rights to use that image.',
        'Please check the ingredients listed.',
        'Please review the instructions as it seems something isn’t quite right.',
        'Please review the title.',
        'In order to be reviewed, this needs to be submitted in English.',
        'We have deemed this unsuitable for The Essential Life App.',
    ];

    /**
     * Perform the action on the given models.
     *
     * @param  \Laravel\Nova\Fields\ActionFields  $fields
     * @param  \Illuminate\Support\Collection  $models
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        $subject = $this->reasonSubjects[$fields['rejection_reason_subject']];
        $description = $fields['rejection_reason_description'];

        $models->each(function (UserGeneratedContent $ugc) use ($subject, $description) {
            $ugc->reject($subject, $description);
        });
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields()
    {
        return [
            Select::make('Reason', 'rejection_reason_subject')->options($this->reasonSubjects),

            Textarea::make('Additional information (Optional)', 'rejection_reason_description'),
        ];
    }
}
