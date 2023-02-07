<?php

namespace Wqa\NovaSortableTableResource;

use Laravel\Nova\Fields\Field;

class TableSortable extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'nova-table-sortable';
}
