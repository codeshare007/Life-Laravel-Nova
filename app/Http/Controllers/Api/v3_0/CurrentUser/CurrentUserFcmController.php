<?php

namespace App\Http\Controllers\Api\v3_0\CurrentUser;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Exceptions\InvalidApiParameterException;

/**
 * @OA\Post(
 *     path="/{lang}/v3.0/currentUser/token",
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
 *     operationId="index",
 *     @OA\RequestBody(
 *         description="Input data format",
 *         required=true,
 *         @OA\MediaType(
 *             mediaType="application/x-www-form-urlencoded",
 *             @OA\Schema(
 *                  required={"fcm_token"},
 *                  @OA\Property(
 *                      property="fcm_token",
 *                      type="string"
 *                  )
 *              )
 *          )
 *     ),
 *     security={{ "bearerAuth": {} }},
 *     @OA\Response(
 *         response=200,
 *         description="successful operation",
 *     ),
 *     @OA\Response(response=401, description="Unauthorized"),
 * )
 */
class CurrentUserFcmController extends Controller
{
    public function __invoke(Request $request, $lang)
    {
        $validation = Validator::make($request->all(), [
            'fcm_token' => 'required',
        ]);

        if ($validation->fails()) {
            throw new InvalidApiParameterException($validation->getMessageBag()->getMessages());
        }

        Auth::user()->update($validation->getData());

        return response()->json([]);
    }
}
