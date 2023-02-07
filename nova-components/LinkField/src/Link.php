<?php

namespace Wqa\LinkField;

use Laravel\Nova\Fields\Field;

class Link extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'link-field';

    public function href(string $val) {
        return $this->withMeta(['href' => $val]);
    }
}
