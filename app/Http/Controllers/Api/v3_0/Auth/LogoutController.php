<?php

namespace App\Http\Controllers\Api\v3_0\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

/**
 * @OA\post(
 *     path="/{lang}/v3.0/auth/logout",
 *     description="Log out",
 *     tags={"Auth3.0"},
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
 *     @OA\Response(
 *         response=200,
 *         description="Successfully logged out",
 *     ),
 *     security={{ "bearerAuth": {} }},
 *     @OA\Response(response=401, description="Unauthorized"),
 * )
 */
class LogoutController extends Controller
{
    public function __invoke(Request $request)
    {
        $request->user()->token()->revoke();

        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }
}
