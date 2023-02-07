<?php

namespace App\Http\Controllers\Api\v3_0\CurrentUser;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\CurrentUser\Settings\UpdateRequest;

class CurrentUserSettingsController extends Controller
{
    /**
     * @OA\Post(
     *     path="/{lang}/v3.0/settings",
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
     *     operationId="show",
     *     security={{ "bearerAuth": {} }},
     *     @OA\Response(response=200, description=""),
     * )
     */
    public function show()
    {
        return api_resource('UserSettingsResource')->make(Auth::user());
    }

    /**
     * @OA\Put(
     *     path="/{lang}/v3.0/settings",
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
     *         description="Input data format",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                  required={"settings"},
     *                  @OA\Property(
     *                      property="settings",
     *                      type="string"
     *                  ),
     *              )
     *          )
     *     ),
     *     security={{ "bearerAuth": {} }},
     *     @OA\Response(response=200, description=""),
     * )
     */
    public function update(UpdateRequest $request)
    {
        Auth::user()->update($request->validated());
    }
}
