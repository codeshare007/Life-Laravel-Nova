<?php

namespace App\Nova;

use App\Ailment;
use App\BodySystem;
use Wqa\NovaExtendFields\Fields\Text;
use Wqa\NovaExtendFields\Fields\Textarea;

abstract class UserGeneratedContentRemedy extends UserGeneratedContent
{
    /**
     * Return the contentType value
     *
     * @return string
     */
    public static function contentType()
    {
        return 'Remedy';
    }

    /**
     * Return the fields for left column
     *
     * @return array
     */
    public function leftColumnFields()
    {
        return [
            Text::make('Name', 'name'),

            Textarea::make('Method', 'content.instructions')->withMeta(['extraAttributes' => [
                'disabled' => true,
            ]]),

            Text::make('Body System', 'content.body_system_id', function() {
                return BodySystem::findByIdOrUuid($this->content['body_system_id'])->name ?? '---';
            })->withMeta(['disabled' => true]),

            Text::make('Ailment', 'content.ailment_id', function() {
                return Ailment::findByIdOrUuid($this->content['ailment_id'])->name ?? '---';
            })->withMeta(['disabled' => true]),
        ];
    }
}
