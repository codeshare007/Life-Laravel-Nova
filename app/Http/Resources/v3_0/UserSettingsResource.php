<?php

namespace App\Http\Resources\v3_0;

class UserSettingsResource extends BaseResource
{
    /**
     * @OA\Schema(
     *     schema="UserSettingResource3.0",
     *     type="object",
     *     title="User Setting Resource V3.0",
     *     @OA\Property(
     *          property="resource_type",
     *          type="string"
     *     ),
     *     @OA\Property(
     *          property="settings",
     *          type="string"
     *     )
     *)
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'resource_type' => 'UserSettings',
            'settings' => json_decode($this->settings),
        ];
    }
}
