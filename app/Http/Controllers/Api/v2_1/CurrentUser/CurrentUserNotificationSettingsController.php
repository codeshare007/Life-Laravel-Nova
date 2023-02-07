<?php

namespace App\Http\Controllers\Api\v2_1\CurrentUser;

use Illuminate\Http\Request;
use BenSampo\Enum\Rules\EnumValue;
use App\Enums\NotificationFrequency;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Exceptions\InvalidApiParameterException;

class CurrentUserNotificationSettingsController extends Controller
{
    public function show()
    {
        $notificationSettings = Auth::user()->notificationSettings;

        return api_resource('NotificationSettingsResource')->make($notificationSettings);
    }

    public function update(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'enabled' => 'required|boolean',
            'notify_for_favourites' => 'required|boolean',
            'frequency' => [
                'required',
                new EnumValue(NotificationFrequency::class)
            ],
        ]);

        if ($validation->fails()) {
            throw new InvalidApiParameterException($validation->getMessageBag()->getMessages());
        }

        Auth::user()->notificationSettings->update($validation->getData());
    }
}
