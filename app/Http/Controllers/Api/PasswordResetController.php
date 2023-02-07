<?php

namespace App\Http\Controllers\Api;

use App\User;
use App\PasswordReset;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Services\LanguageDatabaseService;
use App\Notifications\Mail\PasswordResetRequest;
use App\Notifications\Mail\PasswordResetSuccess;

class PasswordResetController extends Controller
{
    /** @var LanguageDatabaseService */
    protected $languageDatabaseService;

    public function __construct(LanguageDatabaseService $languageDatabaseService)
    {
        $this->languageDatabaseService = $languageDatabaseService;
    }

    /**
     * @OA\Post(
     *     path="/password/create",
     *     tags={"Auth2.1"},
     *     operationId="create",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                  required={"email"},
     *                  @OA\Property(
     *                      property="email",
     *                      type="string"
     *                  )
     *             ),
     *         )
     *     ),
     *     @OA\Response(response=200, description="Thanks, password reset details have been sent to your email.",),
     *     @OA\Response(response=404, description="We can't find a user with that e-mail address."),
     * )
     */
    public function create(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
        ]);

        $user = User::inAnyDbFindBy('email', $request->email);

        if (!$user) {
            return response()->json([
                'message' => __('passwords.user')
            ], 404);
        }

        $this->languageDatabaseService->setLanguage($user->language);

        $passwordReset = PasswordReset::updateOrCreate(['email' => $user->email], [
            'email' => $user->email,
            'token' => Str::random(60),
        ]);

        if ($user && $passwordReset) {
            $user->notify(new PasswordResetRequest($passwordReset->token, $user->email));
        }

        return response()->json([
            'message' => __('passwords.sent')
        ]);
    }

    /**
     * @OA\Post(
     *     path="/password/reset",
     *     tags={"Auth2.1"},
     *     operationId="reset",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                  required={"email", "password", "password_confirmation", "token"},
     *                  @OA\Property(
     *                      property="email",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      property="password",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      property="password_confirmation",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      property="token",
     *                      type="string"
     *                  )
     *             ),
     *         )
     *     ),
     *     @OA\Response(
     *          response=200,
     *          description="ok",
     *          @OA\JsonContent(
     *              type="object"
     *          )
     *     ),
     *     @OA\Response(response=404, description="This password reset token is invalid."),
     * )
     */
    public function reset(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string|confirmed|min:6',
            'token' => 'required|string'
        ]);

        $user = User::inAnyDbFindBy('email', $request->email);

        if (!$user) {
            return response()->json([
                'message' => __('passwords.user')
            ], 404);
        }

        $this->languageDatabaseService->setLanguage($user->language);

        $passwordReset = PasswordReset::where([
            ['token', $request->token],
            ['email', $request->email]
        ])->first();

        if (!$passwordReset) {
            return response()->json([
                'message' => __('passwords.token')
            ], 404);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        $passwordReset->delete();

        $user->notify(new PasswordResetSuccess($passwordReset));

        return response()->json($user);
    }
}
