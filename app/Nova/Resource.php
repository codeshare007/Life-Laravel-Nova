<?php

namespace App\Nova;

use Illuminate\Database\Eloquent\Relations\MorphPivot;
use Laravel\Nova\Http\Requests\NovaRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use phpDocumentor\Reflection\Types\Boolean;
use Wqa\NovaExtendResources\Card;
use Wqa\NovaExtendResources\Resource as ExtendResource;

abstract class Resource extends ExtendResource
{
    protected $fields;

    /**
     * The number of resources to show per page via relationships.
     *
     * @var int
     */
    public static $perPageViaRelationship = 20;

    /**
     * Create a new resource instance.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $resource
     * @return void
     */
    public function __construct($resource)
    {
        parent::__construct($resource);

        if ($resource->sortable) {
            $this->defaultSortBy = [
                'order_by' => 'sort_order',
                'order_way' => 'asc'
            ];
        }
    }

    public function fields(Request $request)
    {
        if ($this->isIndexResourceView() && method_exists($this, 'fieldsForIndex')) {
            return $this->fieldsForIndex();
        }

        $this->setFieldsLayout($request);

        return array_merge($this->fields, $this->tools());
    }

    protected function setFieldsLayout(Request $request)
    {
        $defaultLayout = [
            'leftColumn' => method_exists($this, 'rightColumnFields') ? '2/3' : 'full',
            'rightColumn' => '1/3'
        ];

        $layout = $request->layout ?? $defaultLayout;

        foreach ($layout as $column => $width) {
            if (method_exists($this, $column.'Fields')) {
                $this->fields[$column] = (new Card($column, function() use ($column) {
                    return $this->{$column.'Fields'}();
                }))->width($width);
            }
        }

        $this->fields = collect($this->fields)->flatten()->toArray();
    }

    /**
     * Build an "index" query for the given resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function indexQuery(NovaRequest $request, $query)
    {
        return $query;
    }

    /**
     * Build a Scout search query for the given resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  \Laravel\Scout\Builder  $query
     * @return \Laravel\Scout\Builder
     */
    public static function scoutQuery(NovaRequest $request, $query)
    {
        return $query;
    }

    /**
     * Build a "detail" query for the given resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function detailQuery(NovaRequest $request, $query)
    {
        return parent::detailQuery($request, $query);
    }

    /**
     * Build a "relatable" query for the given resource.
     *
     * This query determines which instances of the model may be attached to other resources.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function relatableQuery(NovaRequest $request, $query)
    {
        return parent::relatableQuery($request, $query);
    }

    /**
     * Fill a new model instance using the given request.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return array
     */
    public static function fill(NovaRequest $request, $model)
    {
        // manage polymorphic - this is a bit hacky, sorry
        if ($request->has('polyTypeKey') && $request->has('polyIdKey')) {
            $model[$request->get('polyTypeKey')] = $request->get('polyType');
            $model[$request->get('polyIdKey')] = $request->get('polyId');
        }

        $fields = parent::fill($request, $model);

        $model->save();

        static::fillForUpdate($request, $model);
        return $fields;
    }

    /**
     * Fill a new model instance using the given request.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return array
     */
    public static function fillForUpdate(NovaRequest $request, $model)
    {
        Collect($request->toArray())->each(function($value, $attribute) use ($model) {
            if (str_contains($attribute, '_list')) {
                static::updateRelatedList($model, $attribute, $value, '_list');
            }
        });

        return parent::fillForUpdate($request, $model);
    }

    /**
     * Update model relationships.
     * @todo Move this to the model class and split out all the logic
     *
     * @param $model
     * @param $attribute
     * @param $value
     * @param $key
     */
    public static function updateRelatedList($model, $attribute, $value, $key)
    {
        if (! $model->isFillable($attribute) && ends_with($attribute, $key)) {
            $relationKey = camel_case(str_replace($key, '', $attribute));

            $relatedModel = $model->{$relationKey} ?? null;

            if ($relatedModel) {
                if ($value) {
                    $values = explode(',', $value);

                    if ($model->{$relationKey}()->getTable() == 'tagables') {

                        $previousValues = $model->{$relationKey}()->pluck('id')->toArray();
                        if ($previousValues) {
                            $model->{$relationKey}()->detach($previousValues);
                        }

                        $type = $attribute == 'properties' ? 0 : 1;
                        $ids = [];

                        foreach ($values as $value) {
                            if (!is_numeric($value)) {
                                $tag = new \App\Tag();
                                $tag->fill([
                                    'name' => $value,
                                    'type' => $type
                                ]);
                                if ($tag->save()) {
                                    $value = $tag->id;
                                }
                            }
                            $ids[] = (int)$value;
                        }
                        $model->{$relationKey}()->attach($ids);
                    } else {
                        $model->{$relationKey}()->sync($values);
                    }
                } else {
                    $model->{$relationKey}()->detach();
                }
            }
        }
    }

    /**
     * Get the displayable label of the resource.
     *
     * @return string
     */
    public static function label()
    {
        $label = ucwords(Str::snake(class_basename(get_called_class()), ' '));
        if (Str::startsWith($label, 'User Generated Content')) {
            $label = Str::replaceFirst('User Generated Content', '', $label);
        }
        return Str::plural($label);
    }

    /**
     * Check to see if we are on index
     *
     * @return bool
     */
    protected function isIndexResourceView()
    {
        return request()->perPage !== null;
    }

    /**
     * Prepare the resource for JSON serialization.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  \Illuminate\Support\Collection  $fields
     * @return array
     */
    public function serializeForIndex(NovaRequest $request, $fields = null)
    {
        return array_merge($this->serializeWithId($fields ?: $this->indexFields($request)), [
            'authorizedToView' => $this->authorizedToView($request),
            'authorizedToUpdate' => $this->authorizedToUpdateForSerialization($request),
            'authorizedToDelete' => $this->authorizedToDeleteForSerialization($request),
            'authorizedToRestore' => static::softDeletes() && $this->authorizedToRestore($request),
            'authorizedToForceDelete' => static::softDeletes() && $this->authorizedToForceDelete($request),
            'softDeletes' => static::softDeletes(),
            'softDeleted' => $this->isSoftDeleted(),
            'editMode' => $request->editMode
        ]);
    }

    /**
     * Return the tools
     *
     * @return array
     */
    protected function tools()
    {
        return [
        ];
    }
}
