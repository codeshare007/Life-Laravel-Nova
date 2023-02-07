<?php

namespace App\Http\Controllers\Api\v2_1\CurrentUser;

use Illuminate\Http\Request;
use App\Events\UserUsedAppEvent;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

/**
 * @OA\Post(
 *     path="/{lang}/v2.1/currentUser/used-app",
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
 *     operationId="update",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\MediaType(
 *             mediaType="application/x-www-form-urlencoded",
 *             @OA\Schema(
 *                  required={"app_version", "build_number"},
 *                  @OA\Property(
 *                      property="app_version",
 *                      type="string"
 *                  ),
 *                  @OA\Property(
 *                      property="build_number",
 *                      type="string"
 *                  ),
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

        $user->save();
    }
}
