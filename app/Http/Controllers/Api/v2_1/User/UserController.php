<?php

namespace App\Http\Controllers\Api\v2_1\User;

use App\User;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    /**
     * @var User
     */
    private $user;

    /**
     * UserController constructor.
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * @OA\Get(
     *     path="/{lang}/v2.1/users",
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
     *     @OA\Parameter(
     *         name="id",
     *         in="query",
     *         required=true,
     *         type="integer"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                  type="object"
     *             )
     *         ),
     *     ),
     *     security={{ "bearerAuth": {} }},
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=404, description="error"),
     * )
     *
     * @param $id
     * @return \Illuminate\Http\Resources\Json\Resource
     */
    public function show($lang, $id)
    {
        $user = $this->user
            ->with('content.recipe', 'content.remedy', 'remedies.bodySystems')
            ->find($id);
        return api_resource('UserResource')->make($user);
    }
}
