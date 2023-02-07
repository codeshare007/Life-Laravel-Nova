<?php

namespace App\Http\Controllers\Api\CurrentUser;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Exceptions\InvalidApiParameterException;

class CurrentUserNotificationController extends Controller
{
    /**
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
