<?php

namespace Laravel\Nova\Fields;

class SortableToggleField extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'sortable-toggle-field';

    /**
     * Display the field as raw HTML using Vue.
     *
     * @return $this
     */
    public function asHtml()
    {
        return $this->withMeta(['asHtml' => true]);
    }
}
