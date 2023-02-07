<?php

namespace App\Http\Resources\v1_1;

use App\Enums\CardTextStyle;
use App\Enums\CardVerticalAlignment;
use App\Enums\CardHorizontalAlignment;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Enums\CardOverlayStyle;

class CardResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'resource_type' => 'Card',
            'id' => $this->id,
            'title' => $this->title,
            'subtitle' => $this->subtitle,
            'description' => $this->description,
            'url' => $this->url,
            'button_text' => $this->button_text,
            'image_url' => $this->image_url ? Storage::url($this->image_url) : null,
            'header_image_url' => $this->header_image_url ? Storage::url($this->header_image_url) : null,
            'background_color' => $this->background_color,
            'overlay_style' => CardOverlayStyle::getKey($this->overlay_style),
            'text_style' => CardTextStyle::getKey($this->text_style),
            'content_vertical_alignment' => CardVerticalAlignment::getKey($this->content_vertical_alignment),
            'content_horizontal_alignment' => CardHorizontalAlignment::getKey($this->content_horizontal_alignment),
        ];
    }
}
