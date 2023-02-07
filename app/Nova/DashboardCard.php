<?php

namespace App\Nova;

use App\Rules\HexColor;
use Wqa\LinkField\Link;
use Wqa\Regions\Regions;
use App\Enums\CardTextStyle;
use Illuminate\Http\Request;
use App\Enums\CardOverlayStyle;
use App\Enums\CardVerticalAlignment;
use Wqa\NovaExtendFields\Fields\Text;
use App\Enums\CardHorizontalAlignment;
use Wqa\NovaExtendFields\Fields\Image;
use Wqa\NovaExtendFields\Fields\Select;
use Wqa\CardPreviewTool\CardPreviewTool;
use Wqa\NovaExtendFields\Fields\Textarea;
use Wqa\NovaExtendFields\Fields\BooleanSwitch;
use App\Nova\Filters\DashboardCard\ActiveFilter;
use App\Nova\Filters\DashboardCard\RegionFilter;
use App\Nova\Filters\DashboardCard\ShowForiOSFilter;
use App\Nova\Filters\DashboardCard\ShowForAndroidFilter;
use Wqa\NovaSortableTableResource\Concerns\SortsIndexEntries;

class DashboardCard extends Resource
{
    use SortsIndexEntries;

    /**
     * Create a new resource instance.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $resource
     * @return void
     */
    public function __construct($resource)
    {
        parent::__construct($resource);

        $this->defaultSortBy = [
            'order_by' => 'sort_order',
            'order_way' => 'asc'
        ];
    }

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\Card';

    /**
     * The logical group associated with the resource.
     *
     * @var string
     */
    public static $group = ['Dashboard'];

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'title';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
        'title',
    ];

    public static function label()
    {
        return 'Cards';
    }

    /**
     * Return the fields for the index view.
     *
     * @return array
     */
    public function fieldsForIndex()
    {
        if (request()->editMode) {
            return [
                Text::make('Title'),

                BooleanSwitch::make('Show for iOS', 'is_visible_on_ios'),

                BooleanSwitch::make('Show for Android', 'is_visible_on_android'),

                BooleanSwitch::make('Active', 'is_active')->hideLabel(),

                Regions::make('Region Visibility', 'regions'),
            ];
        }

        return [
            Link::make('Title')->href('/admin/resources/dashboard-cards/' . $this->id)->sortable(),

            BooleanSwitch::make('Show for iOS', 'is_visible_on_ios'),

            BooleanSwitch::make('Show for Android', 'is_visible_on_android'),

            BooleanSwitch::make('Active', 'is_active')->hideLabel(),

            Regions::make('Region Visibility', 'regions'),
        ];
    }

    /**
     * Return the fields for left column.
     *
     * @return array
     */
    protected function leftColumnFields()
    {
        return [
            BooleanSwitch::make('Active', 'is_active')->help('Only active cards will show on the dashboard.'),

            Textarea::make('Title'),

            Text::make('Subtitle'),

            Textarea::make('Description'),

            Text::make('Legacy URL (deprecated - use the URL field below instead)', 'url'),

            Text::make('URL', 'url_uuid')->help('
                Enter any web URL. 

                Or enter an email address (this will open the default mail client in the app): mailto:email@example.com
                
                Or use one of the following internal URLs: <br>
                <br>
                Profile: essentiallife://profile<br>
                Favourites tab in profile: essentiallife://favorites<br>
                User generated content tab in profile: essentiallife://ugc<br>
                <br>
                How to guide: essentiallife://popup/how-to-use<br>
                Tip: essentiallife://popup/tips<br>
                Contact: essentiallife://popup/contact<br>
                Share: essentiallife://popup/share<br>
                <br>
                Oils index: essentiallife://resource/Oil<br>
                Blends index: essentiallife://resource/Blend<br>
                Supplements index: essentiallife://resource/Supplement<br>
                Body Systems index: essentiallife://resource/BodySystem<br>
                Recipe index: essentiallife://resource/Recipe<br>
                Remedies index: essentiallife://resource/Remedy<br>
                Ailments index: essentiallife://resource/Ailment<br>
                Symptoms  index: essentiallife://resource/Symptom<br>
                <br>
                Specific resource: essentiallife://resource/{resource}/{id-or-uuid-of-resource} Note: you can get the URL for a resource from the details view in Nova.
            '),

            Text::make('Button Text'),

            Select::make('Text Style')
                ->options(CardTextStyle::toSelectArray())
                ->rules('required')
                ->help('The color of the title, subtitle and description text.'),

            Select::make('Overlay Style')
                ->options(CardOverlayStyle::toSelectArray())
                ->rules('required')
                ->help('The color of the image overlay. You can use this to lighten/darken the background image to make the text more visible.'),

            Select::make('Content Vertical Alignment')
                ->options(CardVerticalAlignment::toSelectArray())
                ->rules('required')
                ->help('The vertical alignment of the text in the card.'),

            Select::make('Content Horizontal Alignment')
                ->options(CardHorizontalAlignment::toSelectArray())
                ->rules('required')
                ->help('The horizontal alignment of the text in the card.'),

            BooleanSwitch::make('Show for iOS', 'is_visible_on_ios')
                ->help('Toggle the visibility of the card for iOS.'),

            BooleanSwitch::make('Show for Android', 'is_visible_on_android')
                ->help('Toggle the visibility of the card for Android.'),

            Regions::make('Region Visibility', 'regions')
                ->help('Toggle the visibility of the card for different regions. The card is visible for regions in green.'),
        ];
    }

    /**
     * Return the fields for right column.
     *
     * @return array
     */
    protected function rightColumnFields()
    {
        return [
            Text::make('Background Color')->rules([
                'nullable',
                new HexColor(),
            ])
                ->help('Use this to give the card a solid background color to the card instead of an image. Enter a hex color code.'),

            Image::make('Background Image', 'image_url', 's3')->rules('image', 'max:2000')
                ->help('The background image fills the background of the card'),

            Image::make('Header Image', 'header_image_url', 's3')->rules('image', 'max:2000')
                ->help('The header image appears above the card text content.'),
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
            CardPreviewTool::make(),
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
        return [];
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
            new RegionFilter(),
            new ActiveFilter(),
            new ShowForiOSFilter(),
            new ShowForAndroidFilter(),
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
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [];
    }
}
