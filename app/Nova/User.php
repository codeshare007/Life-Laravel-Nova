<?php

namespace App\Nova;

use Wqa\LinkField\Link;
use Illuminate\Http\Request;
use Wqa\UserDebug\UserDebug;
use App\Enums\SubscriptionType;
use App\Nova\Lenses\AdminUsers;
use App\Nova\Metrics\UsersPerDay;
use App\Nova\Metrics\UsersPerMonth;
use App\Nova\Metrics\TotalUsersCount;
use Wqa\NovaExtendFields\Fields\Text;
use App\Nova\Metrics\LegacyUsersCount;
use Wqa\NovaExtendFields\Fields\Image;
use Wqa\NovaExtendFields\Fields\Boolean;
use Wqa\NovaExtendFields\Fields\HasMany;
use Wqa\NovaExtendFields\Fields\DateTime;
use Wqa\NovaExtendFields\Fields\Password;
use Wqa\NovaExtendFields\Fields\Textarea;
use App\Nova\Filters\UserSubscriptionType;
use Wqa\NovaExtendFields\Fields\BelongsTo;
use App\Nova\Metrics\ActiveTrialUsersCount;
use App\Nova\Metrics\InactiveTrialUsersCount;
use App\Nova\Metrics\UsersPerSubscriptionType;
use Wqa\NovaExtendFields\Fields\BooleanSwitch;
use App\Nova\Actions\DisableBypassSubscriptionReceiptValidation;
use App\Nova\Actions\EnableBypassSubscriptionReceiptValidation7Days;
use App\Nova\Actions\EnableBypassSubscriptionReceiptValidation14Days;
use App\Nova\Actions\EnableBypassSubscriptionReceiptValidation30Days;

class User extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\\User';

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'name';

    /**
     * The logical group associated with the resource.
     *
     * @var string
     */
    public static $group = ['User'];

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'name', 'email',
    ];

    public function fieldsForIndex()
    {
        return [
            DateTime::make('Created At')->sortable(),

            Link::make('Name')->href('/admin/resources/users/' . $this->id)->sortable(),

            Text::make('Email')->sortable(),

            Text::make('Subscription Type')->resolveUsing(function (SubscriptionType $type) {
                return $type->description;
            })->sortable(),

            DateTime::make('Subscription Expires At')->sortable(),

            Boolean::make('Subscription Active', function () {
                return $this->subscription_expires_at > now();
            }),
        ];
    }

    /**
     * Return the fields for left column.
     *
     * @return array
     */
    public function leftColumnFields()
    {
        return [
            Text::make('Name')
                ->sortable()
                ->rules('required', 'max:255'),

            Textarea::make('Bio'),

            Text::make('Email')
                ->sortable()
                ->rules('required', 'email', 'max:254')
                ->creationRules('unique:users,email')
                ->updateRules('unique:users,email,{{resourceId}}'),

            Password::make('Password')
                ->creationRules('required', 'string', 'min:6')
                ->updateRules('nullable', 'string', 'min:6'),

            Boolean::make('Override Subscription Expiry', 'bypass_subscription_receipt_validation'),
            DateTime::make('Custom Subscription Expiry Date', 'subscription_expires_at'),

            HasMany::make('Favourites'),
        ];
    }

    /**
     * Return the fields for right column.
     *
     * @return array
     */
    public function rightColumnFields()
    {
        return [
            BelongsTo::make('Avatar ID', 'avatar', Avatar::class)->hideFromIndex(),

            Image::make('Custom Avatar', 'avatar_url')->disk('s3')->rules('image', 'max:2000')->hideFromIndex(),

            Textarea::make('Notes'),

            BooleanSwitch::make('Is admin')
                ->help('Being an admin enables the user to log into the CMS.')
                ->hideLabel(),
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [
            (new LegacyUsersCount)->width('1/4'),
            (new ActiveTrialUsersCount)->width('1/4'),
            (new InactiveTrialUsersCount)->width('1/4'),
            (new TotalUsersCount)->width('1/4'),
            (new UsersPerSubscriptionType)->width('1/3'),
            (new UsersPerDay)->width('1/3'),
            (new UsersPerMonth)->width('1/3'),
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
            new UserSubscriptionType,
        ];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function lenses(Request $request)
    {
        return [
            new AdminUsers,
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
            (new EnableBypassSubscriptionReceiptValidation7Days)
                ->onlyOnDetail(),
            (new EnableBypassSubscriptionReceiptValidation14Days)
                ->onlyOnDetail(),
            (new EnableBypassSubscriptionReceiptValidation30Days)
                ->onlyOnDetail(),
            (new DisableBypassSubscriptionReceiptValidation)
                ->onlyOnDetail(),
        ];
    }

    /**
     * Return the tools.
     *
     * @return array
     */
    protected function tools()
    {
        return [
            UserDebug::make(),
        ];
    }
}
