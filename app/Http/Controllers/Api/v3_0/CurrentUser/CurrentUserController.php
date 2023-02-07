<?php

namespace App\Http\Controllers\Api\v3_0\CurrentUser;

use App\Enums\Region;
use Illuminate\Http\Request;
use BenSampo\Enum\Rules\EnumValue;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Exceptions\InvalidApiParameterException;

class CurrentUserController extends Controller
{
    /**
     * @OA\Get(
     *     path="/{lang}/v3.0/currentUser",
     *     description="Fetch the element by uuid",
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
     *     security={{ "bearerAuth": {} }},
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(ref="#/components/schemas/UserGeneratedContentResource3.0")
     *     )
     * )
     */
    public function show()
    {
        $user = Auth::user()->load([
            'content.recipe',
            'content.remedy',
            'remedies.bodySystems',
            'favourites',
        ]);

        return api_resource('UserSensitiveResource')->make($user);
    }

    /**
     * @OA\Put(
     *     path="/{lang}/v3.0/currentUser",
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
     *     @OA\RequestBody(
     *         description="Input data format",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                  required={"email", "avatar_id", "name", "bio"},
     *                  @OA\Property(
     *                      property="email",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      property="avatar_id",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      property="name",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      property="bio",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      property="region_id",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      property="base64_image",
     *                      type="string"
     *                  ),
     *              )
     *          )
     *     ),
     *     security={{ "bearerAuth": {} }},
     *     @OA\Response(
     *         response=200,
     *         description="ok"
     *     ),
     * )
     */
    public function update(Request $request)
    {
        // front end uses "North America" as the default region
        // which in the drop down comes up as 0. We consider no
        // North America as just the normal name on the model
        // so we nullify it before validation for ease. Essentially
        // 0 means the user has no region id
        $input = $request->all();
        unset($input['base64_image']);
        if ($request->get('region_id', 0) === 0) {
            $input['region_id'] = null;
        }

        $validation = Validator::make($input, [
            'email' => 'sometimes|required|email|max:300|unique:users,email,' . Auth::id(),
            'avatar_id' => 'sometimes|required|int|exists:avatars,id',
            'name' => 'sometimes|required|string|max:50|unique:users,name,' . Auth::id(),
            'bio' => 'sometimes|nullable|string|max:1000',
            'region_id' => ['nullable', 'sometimes', new EnumValue(Region::class)],
        ]);

        if ($validation->fails()) {
            throw new InvalidApiParameterException($validation->getMessageBag()->getMessages());
        }

        if (request()->filled('base64_image')) {
            Auth::user()->updateAvatar($request->base64_image);
        }

        Auth::user()->update($validation->getData());

        return response(null, 200);
    }

    public function destroy()
    {
        Auth::user()->delete();

        Auth::logout();
    }
}
