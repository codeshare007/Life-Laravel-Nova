<?php

namespace Wqa\NovaExtendFields\Fields;

use Closure;
use JsonSerializable;
use Illuminate\Support\Str;
use Laravel\Nova\Contracts\Resolvable;
use Illuminate\Support\Traits\Macroable;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Fields\Field as FieldNova;

abstract class Field extends FieldNova
{
    /**
     * The field's assigned card.
     *
     * @var string
     */
    public $card;

    protected $hideLabel = false;

    /**
     * Specify a callback to exclude field on update.
     *
     * @return $this
     */
    public function excludeOnUpdate()
    {
        $this->fillCallback = function() {
            return;
        };

        return $this;
    }

    public function hideLabel()
    {
        $this->hideLabel = true;

        return $this;
    }

    public function showLabel()
    {
        $this->hideLabel = false;

        return $this;
    }

    /**
     * Check to see if we are on index
     *
     * @return bool
     */
    protected function isIndexResourceView()
    {
        return count(request()->segments()) == 2;
    }

    /**
     * Prepare the field for JSON serialization.
     *
     * @return array
     */
    public function jsonSerialize()
    {
        if ($this->isIndexResourceView()) {
            $this->hideLabel();
        }

        return array_merge([
            'component' => $this->component(),
            'prefixComponent' => true,
            'indexName' => $this->name,
            'name' => $this->name,
            'attribute' => $this->attribute,
            'value' => $this->value,
            'card' => $this->card,
            'panel' => $this->panel,
            'sortable' => $this->sortable,
            'textAlign' => $this->textAlign,
            'hideLabel' => $this->hideLabel,
        ], $this->meta());
    }
}
