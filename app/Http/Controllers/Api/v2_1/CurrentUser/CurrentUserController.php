<?php

namespace App\Http\Controllers\Api\v2_1\CurrentUser;

use App\Ailment;
use App\Avatar;
use App\Enums\ElementType;
use App\Favourite;
use App\Recipe;
use App\Remedy;
use App\Enums\UserLanguage;
use App\User;
use App\UserGeneratedContent;
use BenSampo\Enum\Rules\EnumValue;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Exceptions\InvalidApiParameterException;

class CurrentUserController extends Controller
{
    /**
     * @OA\Get(
     *     path="/{lang}/v2.1/currentUser",
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
     *     security={{ "bearerAuth": {} }},
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(ref="#/components/schemas/UserGeneratedContentResource2.1")
     *     )
     * )
     */
    public function show()
    {
        $user = Auth::user()->load('content.recipe', 'content.remedy', 'remedies.bodySystems');
        $user['favouriteWithData'] = Favourite::with('favouriteable')
            ->where('user_id', Auth::user()->id)
            ->get()
            ->loadMorph(
                'favouriteable',
                [
                    Ailment::class => ['bodySystems'],
                    Recipe::class => ['categories'],
                    Remedy::class => ['bodySystems', 'ailment']
                ]
            );
        return api_resource('UserSensitiveResource')->make($user);
    }

    public function update(Request $request)
    {
        // front end uses "North America" as the default region
        // which in the drop down comes up as 0. We consider no
        // North America as just the normal name on the model
        // so we nullify it before validation for ease. Essentially
        // 0 means the user has no region id
        $input = $request->all();
        if ($request->get('region_id', 0) === 0) {
            $input['region_id'] = null;
        }

        $validation = Validator::make($input, [
            'email' => 'sometimes|required|email|max:300|unique:users,email,' . Auth::id(),
            'avatar_id' => 'sometimes|required|int|exists:avatars,id',
            'name' => 'required|string|min:3|max:50|unique:users,name,' . Auth::id(),
            'bio' => 'nullable|string|max:1000',
            'region_id' => 'nullable|sometimes|exists:regions,id'
        ]);

        if ($validation->fails()) {
            throw new InvalidApiParameterException($validation->getMessageBag()->getMessages());
        }

        if (request()->filled('base64_image')) {
            Auth::user()->updateAvatar($request->base64_image);
        }

        Auth::user()->update($validation->getData());

        $user = Auth::user()->fresh()->load('content.recipe', 'content.remedy', 'remedies.bodySystems');
        $user['favouriteWithData'] = Favourite::with('favouriteable')
            ->where('user_id', Auth::user()->id)
            ->get()
            ->loadMorph(
                'favouriteable',
                [
                    Ailment::class => ['bodySystems'],
                    Recipe::class => ['categories'],
                    Remedy::class => ['bodySystems', 'ailment']
                ]
            );
        return api_resource('UserSensitiveResource')->make($user);
    }

    public function destroy()
    {
        Auth::user()->delete();

        Auth::logout();
    }

    /**
     * @OA\Post(
     *     path="/{lang}/v2.1/currentUser/token",
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
     *     operationId="fcmToken",
     *     security={{ "bearerAuth": {} }},
     *     @OA\RequestBody(
     *         description="Input data format",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                  required={"fcm_token"},
     *                  @OA\Property(
     *                      property="fcm_token",
     *                      type="string"
     *                  )
     *              )
     *          )
     *     ),
     *     @OA\Response(
     *          response=200,
     *          description="successful operation",
     *       ),
     *     @OA\Response(response=401, description="Unauthorized"),
     * )
     */
    public function fcmToken(Request $request, $lang)
    {
        $validation = Validator::make($request->all(), [
            'fcm_token' => 'required',
        ]);

        if ($validation->fails()) {
            throw new InvalidApiParameterException($validation->getMessageBag()->getMessages());
        }

        Auth::user()->update($validation->getData());

        return response()->json([]);
    }
}
