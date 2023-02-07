<?php

namespace App\Http\Resources\v3_0;

/**
 * @OA\Schema(
 *     schema="InAppNotificationResource3.0",
 *     type="object",
 *     title="InApp Notification Resource V3.0",
 *     @OA\Property(
 *          property="resource_type",
 *          type="string"
 *     ),
 *     @OA\Property(
 *          property="notification_type",
 *          type="string"
 *     ),
 *     @OA\Property(
 *          property="id",
 *          type="integer"
 *     ),
 *     @OA\Property(
 *          property="data",
 *          type="string"
 *     ),
 *     @OA\Property(
 *          property="created_at",
 *          type="date-time"
 *     ),
 *     @OA\Property(
 *          property="read_at",
 *          type="date-time",
 *          description="Flag if remedy is featured"
 *     ),
 *     @OA\Property(
 *          property="expires_at",
 *          type="date-time",
 *          description="Remedy body"
 *     )
 *)
 */
class InAppNotificationResource extends BaseResource
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
            'resource_type' => 'InAppNotification',
            'notification_type' => class_basename($this->type),
            'id' => $this->id,
            'data' => $this->data,
            'created_at' => $this->created_at->toDateTimeString(),
            'read_at' => $this->read_at ? $this->read_at->toDateTimeString() : null,
            'expires_at' => $this->expires_at,
        ];
    }
}
