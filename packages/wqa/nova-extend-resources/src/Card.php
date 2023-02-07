<?php

namespace Wqa\NovaExtendResources;

use JsonSerializable;
use Illuminate\Http\Resources\MergeValue;
use Laravel\Nova\Resource;

class Card extends MergeValue implements JsonSerializable
{
    /**
     * The name of the panel.
     *
     * @var string
     */
    public $name;

    /**
     * The width of the card (1/3, 1/2, or full).
     *
     * @var string
     */
    public $width = '1/3';

    /**
     * Create a new panel instance.
     *
     * @param  string  $name
     * @param  \Closure|array  $fields
     * @return void
     */
    public function __construct($name, $fields = [])
    {
        $this->name = $name;

        parent::__construct($this->prepareFields($fields));
    }

    /**
     * Prepare the given fields.
     *
     * @param  \Closure|array  $fields
     * @return array
     */
    protected function prepareFields($fields)
    {
        return collect(is_callable($fields) ? $fields() : $fields)->each(function ($field) {
            $field->card = $this->name;
        })->all();
    }

    /**
     * Set the width of the card.
     *
     * @param  string  $width
     * @return $this
     */
    public function width($width)
    {
        $this->width = $width;

        return $this;
    }

    /**
     * Get the default panel name for the given resource.
     *
     * @param  \Laravel\Nova\Resource  $resource
     * @return string
     */
    public static function defaultNameFor(Resource $resource)
    {
        return $resource->singularLabel().' '.__('Details');
    }

    /**
     * Prepare the element for JSON serialization.
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'component' => 'card',
            'name' => $this->name,
            'width' => $this->width,
        ];
    }
}
