<?php

namespace App\Http\Controllers\Api\v3_0\CurrentUser;

use Illuminate\Http\Request;
use App\Events\UserUsedAppEvent;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

/**
 * @OA\Post(
 *     path="/{lang}/v3.0/currentUser/used-app",
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
 *     operationId="update",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\MediaType(
 *             mediaType="application/x-www-form-urlencoded",
 *             @OA\Schema(
 *                  required={"app_version", "build_number", "system_version", "device_name"},
 *                  @OA\Property(
 *                      property="app_version",
 *                      type="string"
 *                  ),
 *                  @OA\Property(
 *                      property="build_number",
 *                      type="string"
 *                  ),
 *                  @OA\Property(
 *                      property="system_version",
 *                      type="string"
 *                  ),
 *                  @OA\Property(
 *                      property="device_name",
 *                      type="string"
 *                  )
 *             ),
 *         )
 *     ),
 *     security={{ "bearerAuth": {} }},
 *     @OA\Response(response=200, description=""),
 * )
 */
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

        if ($request->has('system_version')) {
            $user->system_version = $request->system_version;
        }

        if ($request->has('device_name')) {
            $user->device_name = $request->device_name;
        }

        $user->save();

        return response(null, 200);
    }
}
