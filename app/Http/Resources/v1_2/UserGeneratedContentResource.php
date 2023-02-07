<?php

namespace App\Http\Resources\v1_2;

use App\Enums\UserGeneratedContentStatus;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;
use Route;

class UserGeneratedContentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $resourceArr = [
            'resource_type' => 'UserGeneratedContent',
            'id' => $this->id,
            'public_id' => $this->association_id,
            'name' => $this->name,
            'image_url' => $this->image_url ? Storage::url($this->image_url) : '',
            'type' => $this->type,
            'status' => UserGeneratedContentStatus::getKey($this->status),
            'is_public' => $this->is_public,
            'content' => $this->content,
            'rejection_reason_subject' => $this->rejection_reason_subject,
            'rejection_reason_description' => $this->rejection_reason_description,
        ];

        return $resourceArr;
    }
}
