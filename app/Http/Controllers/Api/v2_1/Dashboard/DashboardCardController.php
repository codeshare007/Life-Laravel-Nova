<?php

namespace App\Http\Controllers\Api\v2_1\Dashboard;

use App\Card;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DashboardCardController extends Controller
{
    /**
     * @OA\Get(
     *     path="/{lang}/v2.1/dashboard/cards",
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
     *
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $cards = Card::active()->ordered()->get();

        return api_resource('CardResource')
            ->collection($cards);
    }
}
