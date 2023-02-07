<?php

namespace App\Http\Controllers\Api\v2_1\UserGeneratedContent;

use App\Mail\ContentCreated;
use Illuminate\Http\Request;
use App\UserGeneratedContent;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Enums\UserGeneratedContentStatus;
use App\Http\Requests\UserGeneratedContent\StoreRequest;
use App\Http\Requests\UserGeneratedContent\UpdateRequest;
use Illuminate\Support\Facades\Storage;

class UserGeneratedContentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

        /**
     * @OA\Get(
     *     path="/{lang}/v2.1/userGeneratedContent",
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
     *     security={{ "bearerAuth": {} }},
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(ref="#/components/schemas/UserGeneratedContentResource2.1")
     *     ),
     * )
     */
    public function index(Request $request)
    {
        $userGeneratedContentGroup = $request->user()->content()->orderBy('name')->get()->mapToGroups(function ($item) {
            return [$item['type'] => $item];
        });

        $resourceGroup = [];
        foreach ($userGeneratedContentGroup as $group => $userGeneratedContent) {
            $resourceGroup[$group] = api_resource('UserGeneratedContentResource')->collection($userGeneratedContent);
        }

        return $resourceGroup;
    }

    /**
     * @OA\Get(
     *     path="/{lang}/v2.1/userGeneratedContent/{uuid}",
     *     description="Fetch the element by uuid",
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
     *     @OA\Parameter(
     *         name="uuid",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     security={{ "bearerAuth": {} }},
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(ref="#/components/schemas/UserGeneratedContentResource2.1")
     *     ),
     * )
     */
    public function show($lang, $uuid)
    {
        $userGeneratedContent = UserGeneratedContent::with('recipe', 'remedy')
            ->where('uuid', $uuid)
            ->get()
            ->first();

        return api_resource('UserGeneratedContentEditResource')->make($userGeneratedContent);
    }

    // remove when we go live
    public function tempShow($uuid)
    {
        $userGeneratedContent = UserGeneratedContent::with('recipe', 'remedy')
            ->where('uuid', $uuid)
            ->get()
            ->first();

        return api_resource('UserGeneratedContentEditResource')->make($userGeneratedContent);
    }
    /**
     * @OA\Post(
     *     path="/{lang}/v2.1/userGeneratedContent",
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
     *     @OA\RequestBody(
     *         description="Input data format",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                  required={"uuid", "name", "type", "is_public"},
     *                  @OA\Property(
     *                      property="uuid",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      property="name",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      property="type",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      property="is_public",
     *                      type="boolean"
     *                  ),
     *                  @OA\Property(
     *                      property="content",
     *                      type="array",
     *                      @OA\Items()
     *                  ),
     *                  @OA\Property(
     *                      property="base64_image",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      property="image_url",
     *                      type="string"
     *                  ),
     *              )
     *          )
     *     ),
     *     security={{ "bearerAuth": {} }},
     *     @OA\Response(
     *         response=201,
     *         description="OK",
     *         @OA\JsonContent(ref="#/components/schemas/UserGeneratedContentResource2.1")
     *     ),
     * )
     */
    public function store(StoreRequest $request, $lang)
    {
        $userGeneratedContent = $request->user()->content()->create($request->validated());

        if ($userGeneratedContent && $userGeneratedContent->isPublic == 1) {
            Mail::to($request->user())->send(new ContentCreated($userGeneratedContent->load('user')));
        }

        // Auto Approve
        // if (App::environment(['local', 'staging']) && $userGeneratedContent->is_public === 1) {
        //     $userGeneratedContent->approve();
        // }
        return response()->json(['uuid' => $userGeneratedContent->uuid])
          ->setStatusCode(201);
    }
    /**
     * @OA\Put(
     *     path="/{lang}/v2.1/userGeneratedContent/{uuid}",
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
     *     @OA\Parameter(
     *         name="uuid",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         description="Input data format",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                  required={"name", "type", "is_public", "content"},
     *                  @OA\Property(
     *                      property="name",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      property="type",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      property="is_public",
     *                      type="boolean"
     *                  ),
     *                  @OA\Property(
     *                      property="content",
     *                      type="array",
     *                      @OA\Items()
     *                  ),
     *                  @OA\Property(
     *                      property="base64_image",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      property="image_url",
     *                      type="string"
     *                  ),
     *              )
     *          )
     *     ),
     *     security={{ "bearerAuth": {} }},
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(ref="#/components/schemas/UserGeneratedContentResource2.1")
     *     ),
     * )
     */
    public function update(UpdateRequest $request, $lang, $uuid)
    {
        $userGeneratedContent = UserGeneratedContent::where('uuid', $uuid)
            ->get()
            ->first();

        $hasImage = $userGeneratedContent->image_url;
        $this->authorize('update', $userGeneratedContent);
        $userGeneratedContent->update($request->validated());
        $userGeneratedContent->resubmit();

        $data = [];
        if (
            $userGeneratedContent->image_url &&
            $hasImage !== $userGeneratedContent->image_url
        ) {
            $data['image_url'] = Storage::url($userGeneratedContent->image_url);
        }

        return response()->json($data)
            ->setStatusCode(200);
    }
    /**
     * @OA\Delete(
     *     path="/{lang}/v2.1/userGeneratedContent/{uuid}",
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
     *     @OA\Parameter(
     *         name="uuid",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     security={{ "bearerAuth": {} }},
     *     @OA\Response(
     *         response=204,
     *         description=""
     *     ),
     * )
     */
    public function destroy($lang, $uuid)
    {
        $userGeneratedContent = UserGeneratedContent::where('uuid', $uuid)
            ->get()
            ->first();

        if (!$userGeneratedContent) {
            return response()->json(['Content missing'], 404);
        }

        $this->authorize('delete', $userGeneratedContent);

        $userGeneratedContent->delete();

        if ($userGeneratedContent->publicModel) {
            $userGeneratedContent->publicModel->anonymise();
        }

        return response(null, 204);
    }

    public function tempDestroy($uuid)
    {
        $userGeneratedContent = UserGeneratedContent::where('uuid', $uuid)
            ->get()
            ->first();

        if (!$userGeneratedContent) {
            return response()->json(['Content missing'], 404);
        }

        $this->authorize('delete', $userGeneratedContent);

        $userGeneratedContent->delete();

        if ($userGeneratedContent->publicModel) {
            $userGeneratedContent->publicModel->anonymise();
        }

        return response(null, 204);
    }
}
