<?php

namespace App\Nova\Actions;

use App\Question;
use Illuminate\Bus\Queueable;
use Laravel\Nova\Actions\Action;
use Illuminate\Support\Collection;
use Laravel\Nova\Fields\ActionFields;
use Illuminate\Queue\SerializesModels;
use Wqa\NovaExtendFields\Fields\Select;
use Illuminate\Queue\InteractsWithQueue;
use Wqa\NovaExtendFields\Fields\Textarea;

class RejectQuestion extends Action
{
    use InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The displayable name of the action.
     *
     * @var string
     */
    public $name = 'Reject';

    private $reasonSubjects = [
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
        // $subject = $this->reasonSubjects[$fields['rejection_reason_subject']];
        // $description = $fields['rejection_reason_description'];

        $subject = '';
        $description = '';

        $models->each(function (Question $question) use ($subject, $description) {
            $question->reject($subject, $description);
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
            // Select::make('Reason', 'rejection_reason_subject')->options($this->reasonSubjects),

            // Textarea::make('Additional information (Optional)', 'rejection_reason_description'),
        ];
    }
}
