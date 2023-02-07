<?php

namespace Wqa\NovaExtendFields\Fields;

class ID extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'text-field';

    /**
     * Create a new field.
     *
     * @param  string|null  $name
     * @param  string|null  $attribute
     * @param  mixed|null  $resolveCallback
     * @return void
     */
    public function __construct($name = null, $attribute = null, $resolveCallback = null)
    {
        parent::__construct($name ?? 'ID', $attribute, $resolveCallback);
    }

    /**
     * Create a new, resolved ID field for the givne model.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return static
     */
    public static function forModel($model)
    {
        return tap(static::make('ID', $model->getKeyName()))->resolve($model);
    }
}
