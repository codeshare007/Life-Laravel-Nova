<?php

namespace App\Http\Controllers\Api\v3_0\ContentSuggestion;

use App\ContentSuggestion;
use App\Mail\ContentSuggested;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Enums\ContentSuggestionAssociationType;
use App\Http\Requests\ContentSuggestion\StoreRequest;

class ContentSuggestionController extends Controller
{
    /**
     * @OA\Post(
     *     path="/{lang}/v3.0/contentSuggestion",
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
     *                  required={"name", "type", "mode", "association_type", "association_id"},
     *                  @OA\Property(
     *                      property="name",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      property="type",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      property="mode",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      property="content",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      property="association_type",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      property="association_id",
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
    public function store(StoreRequest $request)
    {
        $contentSuggestion = ContentSuggestion::create([
            'name' => $request->name,
            'type' => $request->type,
            'mode' => $request->mode,
            'content' => $request->content,
            'association_type' => $request->association_type ? ContentSuggestionAssociationType::getValue($request->association_type) : null,
            'association_id' => $request->association_id ?? null,
            'user_id' => Auth::id(),
        ]);

        if ($contentSuggestion) {
            Mail::to(Auth::user())->send(new ContentSuggested($contentSuggestion->load('user')));
        }

        return response(null, 201);
    }
}
