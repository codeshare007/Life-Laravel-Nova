<?php

namespace App\Http\Controllers\Api\Notification;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Notifications\DatabaseNotification as Notification;

class NotificationController extends Controller
{
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Illuminate\Notifications\DatabaseNotification  $notification
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Notification $notification)
    {
        $this->authorize('update', $notification);

        $notification->markAsRead();

        return response(null, 200);
    }
}
