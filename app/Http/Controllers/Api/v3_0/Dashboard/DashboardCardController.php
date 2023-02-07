<?php

namespace App\Http\Controllers\Api\v3_0\Dashboard;

use App\Card;
use App\Http\Controllers\Controller;
use App\Http\Requests\DashboardCardRequest;

class DashboardCardController extends Controller
{
    /**
     * @OA\Get(
     *     path="/{lang}/v3.0/dashboard/cards",
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
     *     @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(type="object",
     *          ),
     *     ),
     *     @OA\Response(response=401, description="Unauthorized"),
     * )
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(DashboardCardRequest $request)
    {
        $cards = Card::active()
            ->forPlatform($request->platform)
            ->forRegion($request->user()->region())
            ->ordered()
            ->get();

        return api_resource('CardResource')
            ->collection($cards);
    }
}
