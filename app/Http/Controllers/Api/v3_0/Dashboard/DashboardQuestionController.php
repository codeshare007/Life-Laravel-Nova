<?php

namespace App\Http\Controllers\Api\v3_0\Dashboard;

use App\Question;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\v3_0\QuestionResource;

class DashboardQuestionController extends Controller
{
    /**
     * @OA\Get(
     *     path="/{lang}/v3.0/dashboard/featured-questions",
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
     *          description="OK",
     *          @OA\JsonContent(
     *              type="array",
     *              @OA\Items(ref="#/components/schemas/QuestionResource3.0")
     *          ),
     *     ),
     *     @OA\Response(response=401, description="Unauthorized"),
     * )
     */
    public function index(Request $request)
    {
        $questions = Question::approved()
            ->featured()
            ->ordered()
            ->get();

        return QuestionResource::collection($questions);
    }
}
