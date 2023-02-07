<?php

namespace Wqa\NovaExtendFields;

//use Laravel\Nova\Fields\Field;
use Wqa\NovaExtendFields\Fields\Field;

class MultiSelectField extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'multi-select-field';

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

    /**
     * Allow custom entries.
     *
     * @return $this
     */
    public function taggable()
    {
        return $this->withMeta(['taggable' => true]);
    }

    /**
     * Display values using their corresponding specified labels.
     *
     * @return $this
     */
    public function displayUsingLabels()
    {
        return $this->displayUsing(function ($value) {
            return collect($this->meta['options'])
                    ->where('value', $value)
                    ->first()['label'] ?? $value;
        });
    }
}
