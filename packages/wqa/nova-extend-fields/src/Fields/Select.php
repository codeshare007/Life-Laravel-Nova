<?php

namespace Wqa\NovaExtendFields\Fields;

class Select extends Field
{
    /**
     * Indicates if the element should be shown on the index view.
     *
     * @var bool
     */
    public $showOnIndex = true;

    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'select-field';

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
