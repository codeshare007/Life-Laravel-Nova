<?php

namespace App\Nova;

use App\Enums\TagType;
use Wqa\LinkField\Link;
use Wqa\NovaExtendFields\Fields\Text;
use Wqa\NovaExtendFields\Fields\Select;

class TagConstituent extends Tag
{
    /**
     * Return the TagType enum value
     *
     * @return string
     */
    public static function tagType()
    {
        return TagType::Constituent;
    }

    /**
     * Return the fields for the index view
     *
     * @return array
     */
    public function fieldsForIndex()
    {
        if (request()->editMode) {
            return [
                Text::make('Name'),
            ];
        }

        return [
            Link::make('Name')->href('/admin/resources/tag-constituents/' . $this->id)->sortable(),
        ];
    }

    /**
     * Return the fields for left column
     *
     * @return array
     */
    public function leftColumnFields()
    {
        return [
            Text::make('Name'),
            
            Select::make('Type')
                ->options(TagType::toSelectArray())
                ->withMeta([
                    'value' => static::tagType(),
                ]),
        ];
    }
}
