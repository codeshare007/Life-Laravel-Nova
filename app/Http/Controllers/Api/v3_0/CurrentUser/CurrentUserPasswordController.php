<?php

namespace App\Http\Controllers\Api\v3_0\CurrentUser;

use App\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\ResetsPasswords;
use App\Exceptions\InvalidApiParameterException;
use App\Exceptions\OriginalPasswordMismatchException;

class CurrentUserPasswordController extends Controller
{
    use ResetsPasswords;

    /**
     * @OA\Put(
     *     path="/{lang}/v3.0/currentUser/password",
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
     *     operationId="update",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                  required={"original_password", "new_password"},
     *                  @OA\Property(
     *                      property="original_password",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      property="new_password",
     *                      type="string"
     *                  )
     *             ),
     *         )
     *     ),
     *     security={{ "bearerAuth": {} }},
     *     @OA\Response(response=404, description="We can't find a user with that e-mail address."),
     * )
     */
    public function update(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'original_password' => 'required',
            'new_password' => 'required',
        ]);

        if ($validation->fails()) {
            throw new InvalidApiParameterException($validation->getMessageBag()->getMessages());
        }

        $user = Auth::user();

        if (! Hash::check($request->original_password, $user->password)) {
            throw new OriginalPasswordMismatchException();
        }

        $user->password = Hash::make($request->new_password);
        $user->setRememberToken(Str::random(60));
        $user->save();
    }
}
