<?php

namespace App\Http\Controllers\Api\v2_1\Avatar;

use App\Avatar;
use App\Http\Controllers\Controller;

class AvatarController extends Controller
{
    /**
     * @OA\Get(
     *     path="/{lang}/v2.1/avatars",
     *     tags={"V2.1-api-token"},
     *     @OA\Parameter(
     *         name="lang",
     *         in="path",
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
     *         description="OK",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items()
     *         ),
     *     ),
     *     security={{ "bearerAuth": {} }},
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=404, description="error"),
     * )
     */
    public function index()
    {
        $avatars = Avatar::all();

        return api_resource('AvatarResource')->collection($avatars);
    }
}
