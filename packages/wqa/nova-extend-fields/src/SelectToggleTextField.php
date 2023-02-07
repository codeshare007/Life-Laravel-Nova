<?php

namespace Wqa\NovaExtendFields;

use Wqa\NovaExtendFields\Fields\Field;

class SelectToggleTextField extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'select-toggle-text-field';

    /**
     * Display the field as raw HTML using Vue.
     *
     * @return $this
     */
    public function asHtml()
    {
        return $this->withMeta(['asHtml' => true]);
    }

    /**
     * Set the options for the select menu.
     *
     * @param  array  $options
     * @return $this
     */
    public function options($options)
    {
        return $this->withMeta([
            'options' => collect($options ?? [])->map(function ($label, $value) {
                return ['label' => $label, 'value' => $value];
            })->values()->all(),
        ]);
    }
}
