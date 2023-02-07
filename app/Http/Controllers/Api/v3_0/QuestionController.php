<?php

namespace App\Http\Controllers\Api\v3_0;

use App\Http\Controllers\Controller;
use App\Http\Requests\Question\StoreRequest;

class QuestionController extends Controller
{
    /**
     * @OA\Post(
     *     path="/{lang}/v3.0/question",
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
     *     operationId="store",
     *     @OA\RequestBody(
     *         description="Input data format",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                  required={"category", "title", "description"},
     *                  @OA\Property(
     *                      property="category",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      property="title",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      property="description",
     *                      type="string"
     *                  )
     *              )
     *          )
     *     ),
     *     security={{ "bearerAuth": {} }},
     *     @OA\Response(response=201, description="ok"),
     *     @OA\Response(response=401, description="Unauthorized"),
     * )
     */
    public function store(StoreRequest $request, $lang)
    {
        request()->user()->questions()->create($request->validated());

        return response(null, 201);
    }
}
