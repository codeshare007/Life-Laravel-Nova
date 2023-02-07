<?php

namespace App\Http\Controllers\Api;

use App\User;
use Carbon\Carbon;
use App\Enums\Platform;
use App\Enums\UserLanguage;
use Illuminate\Http\Request;
use App\Enums\SubscriptionType;
use App\Events\UserLoggedInEvent;
use BenSampo\Enum\Rules\EnumValue;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\UserSignupRequest;
use App\Services\LanguageDatabaseService;
use App\Exceptions\InactiveSubscriptionException;

class AuthController extends Controller
{
    /**
     * @OA\Post(
     *     path="/auth/signup",
     *     tags={"Auth2.1"},
     *     operationId="signup",
     *     @OA\Parameter(
     *         name="lang",
     *         in="query",
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
     *                  required={"email", "password", "name"},
     *                  @OA\Property(
     *                      property="email",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      property="password",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      property="name",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      property="avatar_id",
     *                      type="integer"
     *                  ),
     *              )
     *          )
     *     ),
     *     @OA\Response(
     *          response=200,
     *          description="ok",
     *          @OA\JsonContent(type="object",
     *               @OA\Property(property="message", type="string"),
     *          ),
     *     ),
     * )
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function signup(UserSignupRequest $request)
    {
        User::create($request->userAttributes());

        return response()->json([
            'message' => 'Successfully created user!'
        ], 201);
    }


    /**
     * @OA\Post(
     *     path="/auth/login",
     *     tags={"Auth2.1"},
     *     operationId="login",
     *     @OA\Parameter(
     *         name="lang",
     *         in="query",
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
     *                  required={"email", "password", "platform"},
     *                  @OA\Property(
     *                      property="email",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      property="password",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      property="remember_me",
     *                      type="boolean"
     *                  ),
     *                  @OA\Property(
     *                      property="platform",
     *                      type="string",
     *                  ),
     *                  @OA\Property(
     *                      property="receipt",
     *                      type="string"
     *                  ),
     *              )
     *          )
     *     ),
     *     @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(type="object",
     *               @OA\Property(property="user_id", type="integer"),
     *               @OA\Property(property="language", type="string"),
     *               @OA\Property(property="access_token", type="string"),
     *               @OA\Property(property="token_type", type="string"),
     *               @OA\Property(property="session_expires_at", type="time"),
     *               @OA\Property(property="subscription_type", type="string"),
     *               @OA\Property(property="subscription_expires_at", type="time"),
     *          ),
     *       ),
     *       @OA\Response(response=401, description="Unauthorized"),
     *       @OA\Response(response=400, description="The Japanese language is not supported. Please contact support."),
     * )
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $validateFields = [
            'email' => 'required|string|email',
            'password' => 'required|string',
            'remember_me' => 'boolean',
            'platform' => ['nullable', new EnumValue(Platform::class)],
        ];
        
        // Skip receipt validation for App Testing Only
        if (! ends_with($request->email, '@apple-wqa-dev.test')) {
            $validateFields['receipt'] = 'required|string';
        }

        $request->validate($validateFields);

        $credentials = request(['email', 'password']);
        
        // If auth doesn't succeed on requested DB then iterate over the other DBs until it does and set that as the response DB.
        $selectedLang = UserLanguage::getInstance($request->route()->parameter('lang', UserLanguage::English()->value));
        if (! Auth::attempt($credentials)) {
            /** @var LanguageDatabaseService $languageDatabaseService */
            $languageDatabaseService = resolve(LanguageDatabaseService::class);
            $languageDatabaseService->eachDatabase(function (UserLanguage $language) use ($credentials, &$selectedLang) {
                if (Auth::attempt($credentials)) {
                    $selectedLang = $language;

                    return true;
                }
            });
            $languageDatabaseService->setLanguage($selectedLang);

            // Fix for Android not supporting Japanese language yet.
            if ($selectedLang->is(UserLanguage::Japanese())) {
                return response()->json([
                    'message' => 'The Japanese language is not supported. Please contact support.'
                ], 400);
            }
        }
        
        /** @var User $user */
        if (! $user = $request->user()) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }
        // return response()->json([
        //     'personalAccessToken' => 'ok'
        // ]);
        $personalAccessToken = $user->createToken('Personal Access Token');
        
        // Skip subscription and return fake data for App Testing Only
        if (ends_with($request->email, '@apple-wqa-dev.test')) {
            return response()->json([
                'user_id' => $user->id,
                'language' => $selectedLang->value,
                'access_token' => $personalAccessToken->accessToken,
                'token_type' => 'Bearer',
                'session_expires_at' => Carbon::parse($personalAccessToken->token->expires_at)->toDateTimeString(),
                'subscription_type' => SubscriptionType::TheEssentialLifeMembership12Month()->key,
                'subscription_expires_at' => Carbon::now()->addYear()->toDateTimeString(),
            ]);
        }

        $platform = $request->platform == null ? Platform::iOS() : Platform::getInstance($request->platform);

        if (! $request->receipt || ! $user->hasActiveSubscription($request->receipt, $platform)) {
            throw new InactiveSubscriptionException();
        }

        UserLoggedInEvent::dispatch($user);

        return response()->json([
            'user_id' => $user->id,
            'language' => $selectedLang->value,
            'access_token' => $personalAccessToken->accessToken,
            'token_type' => 'Bearer',
            'session_expires_at' => Carbon::parse($personalAccessToken->token->expires_at)->toDateTimeString(),
            // The App doesn't expect the TheEssentialLifeMembershipTrial type. It will break if we return that value.
            // For now, we'll just fake it and return what would have been returned before the change.
            'subscription_type' => $this->getSubscriptionType($user),
            'subscription_expires_at' => $user->subscription_expires_at ? $user->subscription_expires_at->toDateTimeString() : null,
        ]);
    }

    protected function getSubscriptionType(User $user)
    {
        // The App doesn't expect the TheEssentialLifeMembershipTrial type. It will break if we return that value.
        // For now, we'll just fake it and return what would have been returned before the change.
        if (
            $user->subscription_type &&
            (
                $user->subscription_type->is(SubscriptionType::TheEssentialLifeMembershipTrial) ||
                $user->bypass_subscription_receipt_validation
            )
        ) {
            return SubscriptionType::TheEssentialLifeMembership12Month()->key;
        }

        return $user->subscription_type->key ?? null;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();

        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }
}
