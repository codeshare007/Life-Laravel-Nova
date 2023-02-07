<?php

namespace App\Http\Controllers\Api\v2_1\Notification;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Notifications\DatabaseNotification as Notification;

class NotificationController extends Controller
{
    /**
     * @OA\Put(
     *     path="/{lang}/v2.1/notifications",
     *     tags={"V2.1-api-auth"},
     *     @OA\Parameter(
     *         name="lang",
     *         in="path",
     *         description="language",
     *         required=true,
     *         explode=true,
     *         @OA\Schema(
     *             default="en",
     *             type="string",
     *             enum={"en", "sp", "jp"},
     *         )
     *     ),
     *     security={{ "bearerAuth": {} }},
     *     @OA\Response(
     *         response=200,
     *         description="ok"
     *     ),
     * )
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Illuminate\Notifications\DatabaseNotification  $notification
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $lang, Notification $notification)
    {
        $this->authorize('update', $notification);

        $notification->markAsRead();

        return response(null, 200);
    }
}
