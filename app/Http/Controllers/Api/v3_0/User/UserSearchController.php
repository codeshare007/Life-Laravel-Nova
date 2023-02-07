<?php

namespace App\Http\Controllers\Api\v3_0\User;

use App\User;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\SearchRequest;

class UserSearchController extends Controller
{
    /**
     * @OA\Get(
     *     path="/{lang}/v3.0/user/search",
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
     *     security={{ "bearerAuth": {} }},
     *     @OA\RequestBody(
     *         description="Input data format",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                  @OA\Property(
     *                      property="search",
     *                      type="string"
     *                  ),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *          response=200,
     *          description="OK",
     *          @OA\JsonContent(
     *              type="array",
     *              @OA\Items(ref="#/components/schemas/UserResource3.0")
     *          ),
     *     ),
     *     @OA\Response(response=401, description="Unauthorized"),
     * )
     */
    public function index(SearchRequest $request)
    {
        $users = User::query()
            ->where('name', 'LIKE', $request->search . '%')
            ->limit(10)
            ->orderBy('name')
            ->get();

        return api_resource('UserResource')->collection($users);
    }
}
