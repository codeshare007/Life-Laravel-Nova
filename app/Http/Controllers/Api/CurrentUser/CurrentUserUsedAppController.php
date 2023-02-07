<?php

namespace App\Http\Controllers\Api\CurrentUser;

use Illuminate\Http\Request;
use App\Events\UserUsedAppEvent;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CurrentUserUsedAppController extends Controller
{
    public function __invoke(Request $request)
    {
        $user = Auth::user();

        UserUsedAppEvent::dispatch($user);

        if ($request->has('app_version')) {
            $user->app_version = $request->app_version;
        }
        
        if ($request->has('build_number')) {
            $user->app_build_number = $request->build_number;
        }

        $user->save();
    }
}
