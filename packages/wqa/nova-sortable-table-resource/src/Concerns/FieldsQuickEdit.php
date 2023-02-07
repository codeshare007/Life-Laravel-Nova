<?php

namespace Wqa\NovaSortableTableResource\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Laravel\Nova\Http\Requests\NovaRequest;

trait FieldsQuickEdit
{
    /**
     * Specify that this field should allow quickEdit.
     *
     * @param  bool  $value
     * @return $this
     */
    public function quickEdit($value = true)
    {
        if (! $this->computed()) {
            $this->allowQuickEdit = $value;
        }

        return $this;
    }
}
