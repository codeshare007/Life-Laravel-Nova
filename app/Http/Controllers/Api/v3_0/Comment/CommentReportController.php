<?php

namespace App\Http\Controllers\Api\v3_0\Comment;

use App\CommentReport;
use App\Http\Controllers\Controller;
use App\Http\Requests\CommentReport\StoreRequest;

class CommentReportController extends Controller
{
    /**
     * @OA\Post(
     *     path="/{lang}/v3.0/comment/report",
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
     *                  required={"reporter_id", "commenter_id", "reason", "comment", "firebase_document"},
     *                  @OA\Property(
     *                      property="reporter_id",
     *                      type="integer"
     *                  ),
     *                  @OA\Property(
     *                      property="commenter_id",
     *                      type="integer"
     *                  ),
     *                  @OA\Property(
     *                      property="reason",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      property="comment",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      property="element_uuid",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      property="firebase_document",
     *                      type="string"
     *                  ),
     *              )
     *          )
     *     ),
     *     security={{ "bearerAuth": {} }},
     *     @OA\Response(
     *          response=201, description="ok"
     *       ),
     *     @OA\Response(response=401, description="Unauthorized"),
     * )
     */
    public function store(StoreRequest $request)
    {
        CommentReport::create($request->validated());

        return response(null, 201);
    }
}
