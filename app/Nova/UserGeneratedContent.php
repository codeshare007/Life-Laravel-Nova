<?php

namespace App\Nova;

use Wqa\LinkField\Link;
use Wqa\UgcTool\UgcTool;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Wqa\NovaExtendFields\Fields\Text;
use Wqa\NovaExtendFields\Fields\Image;
use App\Enums\UserGeneratedContentStatus;
use Wqa\NovaExtendFields\Fields\DateTime;
use Laravel\Nova\Http\Requests\NovaRequest;
use App\Nova\Actions\RejectUserGeneratedContent;
use App\Nova\Actions\ApproveUserGeneratedContent;
use Wqa\NovaSortableTableResource\Concerns\SortsIndexEntries;
use App\Nova\Filters\UserGeneratedContentStatus as UserGeneratedContentStatusFilter;

abstract class UserGeneratedContent extends Resource
{
    use SortsIndexEntries;

    /**
     * Determine if the given resource is authorizable.
     *
     * @return bool
     */
    public static function authorizable()
    {
        return false;
    }

    /**
     * Return the fields for the index view.
     *
     * @return array
     */
    public function fieldsForIndex()
    {
        return [
            Image::make('Photo', 'image_url', 's3'),

            Link::make('Name')
                ->href('/admin/resources/' . $this->uriKey() . '/' . $this->id)
                ->sortable(),

            $this->publicModelBelongsTo(),

            BelongsTo::make('User'),

            Text::make('Status', function () {
                if (! $this->isPublic()) {
                    return 'Private';
                }

                return UserGeneratedContentStatus::getDescription($this->status);
            }),

            Text::make('Rejection Reason', function () {
                if ($this->status == UserGeneratedContentStatus::Rejected) {
                    return '<div class="text-red-dark font-semibold">' . $this->rejection_reason_subject . '</div>' . $this->rejection_reason_description;
                }

                return '---';
            })->asHtml(),

            DateTime::make('Created At')->format('MMM DD YYYY')->sortable(),
        ];
    }

    protected function publicModelBelongsTo()
    {
        if ($this->type === 'Recipe') {
            return BelongsTo::make('Public Recipe', 'publicModel', Recipe::class);
        }

        if ($this->type === 'Remedy') {
            return BelongsTo::make('Public Remedy', 'publicModel', Remedy::class);
        }
    }

    /**
     * Return the fields for left column.
     *
     * @return array
     */
    abstract public function leftColumnFields();

    /**
     * Return the fields for right column.
     *
     * @return array
     */
    public function rightColumnFields()
    {
        return [
            Text::make('Status', 'status', function () {
                return UserGeneratedContentStatus::getDescription($this->status);
            })->withMeta(['disabled' => true])->fillUsing(function () {
                return;
            }),

            Text::make('Added by user', 'user', function () {
                return $this->user->name;
            })->withMeta(['disabled' => true])->fillUsing(function () {
                return;
            }),

            Image::make('Photo', 'image_url', 's3')
                ->deletable(false)
                ->rules(['image', 'max:2000'])
                ->creationRules(['required']),
        ];
    }

    abstract public static function contentType();

    abstract public static function isPublic(): bool;

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\UserGeneratedContent';

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
    public static $group = ['  User Generated Content'];

    /**
     * The plural label for the resource.
     *
     * @return string
     */
    public static function label()
    {
        return str_plural('UGC ' . static::contentType()) . (static::isPublic() ? '' : ' (Private)');
    }

    /**
     * The singular label for the resource.
     *
     * @return string
     */
    public static function singularLabel()
    {
        return static::contentType();
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
        return $query
            ->whereIsPublic(static::isPublic())
            ->where('type', static::contentType());
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Return the tools.
     *
     * @return array
     */
    protected function tools()
    {
        return [
            UgcTool::make([
                'heading' => 'Ingredients',
            ])->withMeta([
                'ingredients' => $this->content['ingredients'] ?? [],
            ]),
        ];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [
            new ApproveUserGeneratedContent(),
            new RejectUserGeneratedContent(),
        ];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [
            new UserGeneratedContentStatusFilter(),
        ];
    }
}
