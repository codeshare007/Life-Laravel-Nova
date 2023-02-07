<?php

namespace Wqa\NovaExtendResources;

use ArrayAccess;
use JsonSerializable;
use Illuminate\Support\Str;
use Laravel\Nova\Authorizable;
use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\FillsFields;
use Laravel\Nova\PerformsQueries;
use Laravel\Nova\PerformsValidation;
use Laravel\Nova\ResolvesActions;
use Laravel\Nova\ResolvesCards;
use Laravel\Nova\ResolvesFilters;
use Laravel\Nova\ResolvesLenses;
use Laravel\Scout\Searchable;
use Illuminate\Support\Collection;
use Laravel\Nova\Http\Requests\NovaRequest;
use Illuminate\Contracts\Routing\UrlRoutable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Resources\DelegatesToResource;
use Illuminate\Http\Resources\ConditionallyLoadsAttributes;
use Laravel\Nova\Resource as NovaResource;

abstract class Resource extends NovaResource implements ArrayAccess, JsonSerializable, UrlRoutable
{
    use Authorizable,
        ConditionallyLoadsAttributes,
        DelegatesToResource,
        FillsFields,
        PerformsValidation,
        PerformsQueries,
        ResolvesFields,
        ResolvesFilters,
        ResolvesLenses,
        ResolvesCards;

    /**
     * The underlying model resource instance.
     *
     * @var \Illuminate\Database\Eloquent\Model
     */
    public $resource;

    /**
     * The logical group associated with the resource.
     *
     * @var string
     */
    public static $group = 'Other';

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'id';

    /**
     * The relationships that should be eager loaded when performing an index query.
     *
     * @var array
     */
    public static $with = [];

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [];

    /**
     * Indicates if the resource should be displayed in the sidebar.
     *
     * @var bool
     */
    public static $displayInNavigation = true;

    /**
     * Indicates if the resoruce should be globally searchable.
     *
     * @var bool
     */
    public static $globallySearchable = true;

    /**
     * The number of resources to show per page via relationships.
     *
     * @var int
     */
    public static $perPageViaRelationship = 5;

    /**
     * The cached soft deleting statuses for various resources.
     *
     * @var array
     */
    public static $softDeletes = [];

    /**
     * The default displayable pivot class name.
     *
     * @var string
     */
    const DEFAULT_PIVOT_NAME = 'Pivot';

    /**
     * Create a new resource instance.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $resource
     * @return void
     */
    public function __construct($resource)
    {
        $this->resource = $resource;
    }

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    abstract public function fields(Request $request);

    /**
     * Get the underlying model instance for the resource.
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function model()
    {
        return $this->resource;
    }

    /**
     * Get the logical group associated with the resource.
     *
     * @return string
     */
    public static function group()
    {
        return static::$group;
    }

    /**
     * Determine if this resource is available for navigation.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    public static function availableForNavigation(Request $request)
    {
        return true;
    }

    /**
     * Determine if this resource uses soft deletes.
     *
     * @return bool
     */
    public static function softDeletes()
    {
        if (isset(static::$softDeletes[static::$model])) {
            return static::$softDeletes[static::$model];
        }

        return static::$softDeletes[static::$model] = in_array(
            SoftDeletes::class, class_uses_recursive(static::newModel())
        );
    }

    /**
     * Determine if this resource is searchable.
     *
     * @return bool
     */
    public static function searchable()
    {
        return ! empty(static::$search) || static::usesScout();
    }

    /**
     * Determine if this resource uses Laravel Scout.
     *
     * @return bool
     */
    public static function usesScout()
    {
        return in_array(Searchable::class, class_uses_recursive(static::newModel()));
    }

    /**
     * Get the searchable columns for the resource.
     *
     * @return array
     */
    public static function searchableColumns()
    {
        return empty(static::$search)
                    ? [static::newModel()->getKeyName()]
                    : static::$search;
    }

    /**
     * Get the displayable label of the resource.
     *
     * @return string
     */
    public static function label()
    {
        return Str::plural(class_basename(get_called_class()));
    }

    /**
     * Get the displayable singular label of the resource.
     *
     * @return string
     */
    public static function singularLabel()
    {
        return Str::singular(static::label());
    }

    /**
     * Get the value that should be displayed to represent the resource.
     *
     * @return string
     */
    public function title()
    {
        return $this->{static::$title};
    }

    /**
     * Get the search result subtitle for the resource.
     *
     * @return string
     */
    public function subtitle()
    {
        return null;
    }

    /**
     * Get a fresh instance of the model represented by the resource.
     *
     * @return mixed
     */
    public static function newModel()
    {
        $model = static::$model;

        return new $model;
    }

    /**
     * Get the URI key for the resource.
     *
     * @return string
     */
    public static function uriKey()
    {
        return Str::plural(Str::snake(class_basename(get_called_class()), '-'));
    }

    /**
     * Filter and authorize the given values.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  array  $values
     * @return \Illuminate\Support\Collection
     */
    protected function filterAndAuthorize(NovaRequest $request, $values)
    {
        return collect(
            array_values($this->filter($values))
        )->filter->authorize($request, $request->newResource())->values();
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
        ]);
    }

    /**
     * Prepare the resource for JSON serialization.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function serializeForDetail(NovaRequest $request)
    {
        return array_merge($this->serializeWithId($this->detailFields($request)), [
            'authorizedToUpdate' => $this->authorizedToUpdate($request),
            'authorizedToDelete' => $this->authorizedToDelete($request),
            'authorizedToRestore' => static::softDeletes() && $this->authorizedToRestore($request),
            'authorizedToForceDelete' => static::softDeletes() && $this->authorizedToForceDelete($request),
            'softDeletes' => static::softDeletes(),
            'softDeleted' => $this->isSoftDeleted(),
        ]);
    }

    /**
     * Determine if the resource may be updated, factoring in attachments.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return bool
     */
    protected function authorizedToUpdateForSerialization(NovaRequest $request)
    {
        if ($request->viaManyToMany()) {
            return $request->findParentResourceOrFail()->authorizedToAttach(
                $request, $this->model()
            );
        }

        return $this->authorizedToUpdate($request);
    }

    /**
     * Determine if the resource may be deleted, factoring in detachments.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return bool
     */
    protected function authorizedToDeleteForSerialization(NovaRequest $request)
    {
        if ($request->viaManyToMany()) {
            return $request->findParentResourceOrFail()->authorizedToDetach(
                $request, $this->model(), $request->viaRelationship
            );
        }

        return $this->authorizedToDelete($request);
    }

    /**
     * Determine if the resource is soft deleted.
     *
     * @return bool
     */
    public function isSoftDeleted()
    {
        return static::softDeletes() &&
               ! is_null($this->resource->{$this->resource->getDeletedAtColumn()});
    }

    /**
     * Prepare the resource for JSON serialization.
     *
     * @return array
     */
    public function jsonSerialize()
    {
        $this->serializeWithId($this->resolveFields(
            resolve(Request::class)
        ));
    }

    /**
     * Prepare the resource for JSON serialization using the given fields.
     *
     * @param  \Illuminate\Support\Collection  $fields
     * @return array
     */
    protected function serializeWithId(Collection $fields)
    {
        return [
            'id' => $fields->whereInstanceOf(ID::class)->first() ?: ID::forModel($this->resource),
            'fields' => $fields->all(),
        ];
    }

    public static function fillQuickEditFields(array $updatedModelData, $model)
    {
        $fakeRequest = new NovaRequest(array_merge($updatedModelData, [
            'editMode' => true,
            'perPage' => 1,
        ]));

        // Replace a instance of the request in the service container so that the request() helper method resolves this fake instance
        app()->instance('request', $fakeRequest);
        
        $updatedFieldNames = array_keys($updatedModelData);
        $fields = (new static($model))->indexFields($fakeRequest)->filter(function($field) use ($updatedFieldNames) {
            return in_array($field->attribute, $updatedFieldNames);
        });

        return static::fillFields($fakeRequest, $model, $fields);
    }

    /**
     * Returns the individual action authorizations.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     *
     * @return array
     */
    public function getIndividualActionAuthorizations(NovaRequest $request)
    {
        // Determine the actions that are avaialble for the request
        $actions = $this->availableActions($request);

        // Include the pivot actions that are available for the request
        $actions = $actions->merge($this->availablePivotActions($request));

        // Flatten the action groups
        $actions = $actions->flattenActionGroups();

        // Initialize the authorizations
        $authorizations = [];

        // Iterate through the actions
        foreach($actions as $action) {

            // Add the individual authorization for each action
            $authorizations[$action->uriKey()] = method_exists($action, 'authorizedToSeeIndividual')
                ? $action->authorizedToSeeIndividual($request, $this->resource)
                : true;

        }

        // Return the authorizations
        return $authorizations;
    }
}
