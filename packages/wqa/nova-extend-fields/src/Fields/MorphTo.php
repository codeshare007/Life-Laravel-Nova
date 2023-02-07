<?php

namespace Wqa\NovaExtendFields\Fields;

use Closure;
use Laravel\Nova\Nova;
use Laravel\Nova\Resource;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Laravel\Nova\TrashedStatus;
use Laravel\Nova\Rules\Relatable;
use Laravel\Nova\Http\Requests\NovaRequest;
use Illuminate\Database\Eloquent\Relations\Relation;

class MorphTo extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'morph-to-field';

    /**
     * The class name of the related resource.
     *
     * @var string
     */
    public $resourceClass;

    /**
     * The URI key of the related resource.
     *
     * @var string
     */
    public $resourceName;

    /**
     * The name of the Eloquent "morph to to" relationship.
     *
     * @var string
     */
    public $morphToRelationship;

    /**
     * The key of the related Eloquent model.
     *
     * @var string
     */
    public $morphToId;

    /**
     * The type of the related Eloquent model.
     *
     * @var string
     */
    public $morphToType;

    /**
     * The types of resources that may be polymorphically related to this resource.
     *
     * @var array
     */
    public $morphToTypes = [];

    /**
     * The column that should be displayed for the field.
     *
     * @var \Closure|array
     */
    public $display;

    /**
     * Indicates if the field is nullable.
     *
     * @var bool
     */
    public $nullable = false;

    /**
     * Indicates if this relationship is searchable.
     *
     * @var bool
     */
    public $searchable = false;

    /**
     * The attribute that is the inverse of this relationship.
     *
     * @var string
     */
    public $inverse;

    /**
     * Create a new field.
     *
     * @param  string  $name
     * @param  string|null  $attribute
     * @return void
     */
    public function __construct($name, $attribute = null)
    {
        parent::__construct($name, $attribute);

        $this->morphToRelationship = $this->attribute;
    }

    /**
     * Determine if the field should be displayed for the given request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    public function authorize(Request $request)
    {
        if (! $this->isNotRedundant($request)) {
            return false;
        }

        if (! $this->resourceClass) {
            return true && parent::authorize($request);
        }

        return call_user_func(
            [$this->resourceClass, 'authorizedToViewAny'], $request
        ) && parent::authorize($request);
    }

    /**
     * Determine if the field is not redundant.
     *
     * See: Explanation on belongsTo field.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    public function isNotRedundant(Request $request)
    {
        return (! $request->isMethod('GET') || ! $request->viaResource) ||
               ($this->resourceName !== $request->viaResource);
    }

    /**
     * Resolve the field's value.
     *
     * @param  mixed  $resource
     * @param  string|null  $attribute
     * @return void
     */
    public function resolve($resource, $attribute = null)
    {
        $value = $resource->{$this->attribute}()->withoutGlobalScopes()->getResults();

        [$this->morphToId, $this->morphToType] = [
            optional($value)->getKey(),
            $this->resolveMorphType($resource),
        ];

        if ($resourceClass = $this->resolveResourceClass($value)) {
            $this->resourceName = $resourceClass::uriKey();
        }

        if ($value) {
            $this->value = $this->formatDisplayValue(
                $value, Nova::resourceForModel($value)
            );
        }
    }

    /**
     * Resolve the current resource key for the resource's morph type.
     *
     * @param  mixed  $resource
     * @return string|null
     */
    protected function resolveMorphType($resource)
    {
        if (! $type = optional($resource->{$this->attribute}())->getMorphType()) {
            return;
        }

        if ($morphResource = Nova::resourceForModel($resource->{$type})) {
            return $morphResource::uriKey();
        }
    }

    /**
     * Resolve the resource class for the field.
     *
     * @param  \Illuminate\Database\Eloquent\Model
     * @return string|null
     */
    protected function resolveResourceClass($model)
    {
        return $this->resourceClass = Nova::resourceForModel($model);
    }

    /**
     * Get the validation rules for this field.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function getRules(NovaRequest $request)
    {
        $possibleTypes = collect($this->morphToTypes)->map->value->values();

        return array_merge_recursive(parent::getRules($request), [
            $this->attribute.'_type' => [$this->nullable ? 'nullable' : 'required', 'in:'.$possibleTypes->implode(',')],
            $this->attribute => array_filter([$this->nullable ? 'nullable' : 'required', $this->getRelatableRule($request)]),
        ]);
    }

    /**
     * Get the validation rule to verify that the selected model is relatable.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return \Laravel\Nova\Rules\Relatable|null
     */
    protected function getRelatableRule(NovaRequest $request)
    {
        if ($relatedResource = Nova::resourceForKey($request->{$this->attribute.'_type'})) {
            return new Relatable($request, $this->buildMorphableQuery(
                $request, $relatedResource, $request->{$this->attribute.'_trashed'} === 'true'
            ));
        }
    }

    /**
     * Hydrate the given attribute on the model based on the incoming request.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  object  $model
     * @return void
     */
    public function fill(NovaRequest $request, $model)
    {
        $instance = Nova::modelInstanceForKey($request->{$this->attribute.'_type'});

        if ($instance) {
            $model->{$model->{$this->attribute}()->getMorphType()} = $this->getMorphAliasForClass(
                get_class($instance)
            );
        }

        parent::fillInto($request, $model, $model->{$this->attribute}()->getForeignKey());
    }

    /**
     * Get the morph type alias for the given class.
     *
     * @param  string  $class
     * @return string
     */
    protected function getMorphAliasForClass($class)
    {
        foreach (Relation::$morphMap as $alias => $model) {
            if ($model == $class) {
                return $alias;
            }
        }

        return $class;
    }

    /**
     * Build an morphable query for the field.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  string  $relatedResource
     * @param  bool  $withTrashed
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function buildMorphableQuery(NovaRequest $request, $relatedResource, $withTrashed = false)
    {
        $model = $relatedResource::newModel();

        $query = $request->first === 'true'
                        ? $model->newQueryWithoutScopes()->whereKey($request->current)
                        : $relatedResource::buildIndexQuery(
                                $request, $model->newQuery(), $request->search,
                                [], [], TrashedStatus::fromBoolean($withTrashed)
                          );

        return $query->tap(function ($query) use ($request, $relatedResource, $model) {
            forward_static_call(
                $this->morphableQueryCallable($request, $relatedResource, $model),
                $request, $query
            );
        });
    }

    /**
     * Get the morphable query method name.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  string  $relatedResource
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return array
     */
    protected function morphableQueryCallable(NovaRequest $request, $relatedResource, $model)
    {
        return ($method = $this->morphableQueryMethod($request, $model))
                    ? [$request->resource(), $method]
                    : [$relatedResource, 'relatableQuery'];
    }

    /**
     * Get the morphable query method name.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return string
     */
    protected function morphableQueryMethod(NovaRequest $request, $model)
    {
        $method = 'relatable'.Str::plural(class_basename($model));

        return method_exists($request->resource(), $method) ? $method : null;
    }

    /**
     * Format the given morphable resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  mixed  $resource
     * @param  string  $relatedResource
     * @return array
     */
    public function formatMorphableResource(NovaRequest $request, $resource, $relatedResource)
    {
        return array_filter([
            'avatar' => $resource->resolveAvatarUrl($request),
            'display' => $this->formatDisplayValue($resource, $relatedResource),
            'value' => $resource->getKey(),
        ]);
    }

    /**
     * Format the associatable display value.
     *
     * @param  mixed  $resource
     * @param  string  $relatedResource
     * @return string
     */
    protected function formatDisplayValue($resource, $relatedResource)
    {
        if (! $resource instanceof Resource) {
            $resource = Nova::newResourceFromModel($resource);
        }

        if ($display = $this->displayFor($relatedResource)) {
            return call_user_func($display, $resource);
        }

        return $resource->title();
    }

    /**
     * Set the types of resources that may be related to the resource.
     *
     * @param  array  $types
     * @return $this
     */
    public function types(array $types)
    {
        $this->morphToTypes = collect($types)->map(function ($display, $key) {
            return [
                'type' => is_numeric($key) ? $display : $key,
                'singularLabel' => is_numeric($key) ? $display::singularLabel() : $key::singularLabel(),
                'display' => (is_string($display) && is_numeric($key)) ? $display::singularLabel() : $display,
                'value' => is_numeric($key) ? $display::uriKey() : $key::uriKey(),
            ];
        })->values()->all();

        return $this;
    }

    /**
     * Set the column that should be displayed for the field.
     *
     * @param  \Closure|array|string  $display
     * @return $this
     */
    public function display($display)
    {
        if (is_array($display)) {
            $this->display = collect($display)->mapWithKeys(function ($display, $type) {
                return [$type => $this->ensureDisplayerIsClosure($display)];
            })->all();
        } else {
            $this->display = $this->ensureDisplayerIsClosure($display);
        }

        return $this;
    }

    /**
     * Ensure the given displayer is a Closure.
     *
     * @param  \Closure|string  $display
     * @return \Closure
     */
    protected function ensureDisplayerIsClosure($display)
    {
        return $display instanceof Closure
                    ? $display
                    : function ($resource) use ($display) {
                        return $resource->{$display};
                    };
    }

    /**
     * Get the column that should be displayed for a given type.
     *
     * @param  string  $type
     * @return \Closure
     */
    public function displayFor($type)
    {
        if (is_array($this->display) && $type) {
            return $this->display[$type] ?? null;
        }

        return $this->display;
    }

    /**
     * Specify if the relationship should be searchable.
     *
     * @param  bool  $value
     * @return $this
     */
    public function searchable($value = true)
    {
        $this->searchable = $value;

        return $this;
    }

    /**
     * Set the attribute name of the inverse of the relationship.
     *
     * @param  string  $inverse
     * @return $this
     */
    public function inverse($inverse)
    {
        $this->inverse = $inverse;

        return $this;
    }

    /**
     * Indicate that the field should be nullable.
     *
     * @param  bool  $nullable
     * @return $this
     */
    public function nullable($nullable = true)
    {
        $this->nullable = $nullable;

        return $this;
    }

    /**
     * Get additional meta information to merge with the field payload.
     *
     * @return array
     */
    public function meta()
    {
        $resourceClass = $this->resourceClass;

        return array_merge([
            'resourceName' => $this->resourceName,
            'resourceLabel' => $resourceClass ? $resourceClass::singularLabel() : null,
            'morphToRelationship' => $this->morphToRelationship,
            'morphToTypes' => $this->morphToTypes,
            'morphToType' => $this->morphToType,
            'morphToId' => $this->morphToId,
            'nullable' => $this->nullable,
            'searchable' => $this->searchable,
        ], $this->meta);
    }
}
