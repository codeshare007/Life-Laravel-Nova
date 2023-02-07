<?php

namespace Wqa\Regions;

use App\Enums\Region as RegionEnum;
use Wqa\NovaExtendFields\Fields\Field;
use Laravel\Nova\Http\Requests\NovaRequest;

class Regions extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'regions';

    public function __construct($name, $attribute = null, $resolveCallback = null)
    {
        parent::__construct($name, $attribute, $resolveCallback);

        $this->meta['region_options'] = collect(RegionEnum::getInstances())->sortBy('description')->values();
    }

    protected function fillAttributeFromRequest(NovaRequest $request, $requestAttribute, $model, $attribute)
    {
        if ($request->exists($requestAttribute)) {
            $model->{$attribute} = json_decode($request[$requestAttribute]);
        }
    }
}
