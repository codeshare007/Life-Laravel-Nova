<?php

namespace App\Nova;

use App\Enums\TagType;
use Wqa\LinkField\Link;
use Illuminate\Http\Request;
use Wqa\NovaExtendFields\Fields\Text;
use Wqa\NovaExtendFields\Fields\Select;
use Laravel\Nova\Http\Requests\NovaRequest;

abstract class Tag extends Resource
{
    /**
     * Return the fields for the index view
     *
     * @return array
     */
    abstract public function fieldsForIndex();

    /**
     * Return the fields for left column
     *
     * @return array
     */
    abstract public function leftColumnFields();
    
    /**
     * Return the TagType enum value
     *
     * @return string
     */
    abstract public static function tagType();

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\Tag';

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'name';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
        'name',
    ];

    /**
     * The logical group associated with the resource.
     *
     * @var string
     */
    public static $group = ['Tags'];

    /**
     * The plural label for the resource
     *
     * @return string
     */
    public static function label()
    {
        return str_plural(TagType::getDescription(static::tagType()));
    }

    /**
     * The singular label for the resource
     *
     * @return string
     */
    public static function singularLabel()
    {
        return TagType::getDescription(static::tagType()) . ' Tag';
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
        return $query->where('type', static::tagType());
    }
}
