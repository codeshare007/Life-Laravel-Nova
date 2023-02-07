<?php

namespace App\Nova;

use App\Enums\TagType;
use Wqa\LinkField\Link;
use Wqa\NovaExtendFields\Fields\Text;
use Wqa\NovaExtendFields\Fields\Select;
use Wqa\NovaExtendFields\Fields\Textarea;

class TagProperty extends Tag
{
    /**
     * Return the TagType enum value
     *
     * @return string
     */
    public static function tagType()
    {
        return TagType::Property;
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

                Textarea::make('Description'),
            ];
        }

        return [
            Link::make('Name')->href('/admin/resources/tag-properties/' . $this->id)->sortable(),

            Textarea::make('Description'),
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

            Textarea::make('Description'),
            
            Select::make('Type')
                ->options(TagType::toSelectArray())
                ->withMeta([
                    'value' => static::tagType(),
                ]),
        ];
    }
}
