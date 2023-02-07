<?php

namespace App\Http\Controllers\Api\v3_0\CurrentUser;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Exceptions\InvalidApiParameterException;

class CurrentUserNotificationController extends Controller
{
    /**
     * @OA\Get(
     *     path="/{lang}/v3.0/currentUser/notifications",
     *     tags={"V3.0-api-auth"},
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
     *         description="OK",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/InAppNotificationResource3.0")
     *         ),
     *     ),
     * )
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'include_read' => 'sometimes|boolean',
        ]);

        if ($validation->fails()) {
            throw new InvalidApiParameterException($validation->getMessageBag()->getMessages());
        }

        $includeRead = $request->include_read ?? false;

        $notifications = $includeRead ? $request->user()->notifications : $request->user()->unreadNotifications;

        return api_resource('InAppNotificationResource')->collection($notifications);
    }
}
