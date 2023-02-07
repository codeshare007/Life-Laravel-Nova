<?php

namespace App\Http\Controllers\Api\v3_0\Auth;

use App\User;
use App\Enums\UserLanguage;
use App\Events\UserLoggedInEvent;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Services\LanguageDatabaseService;
use App\Http\Requests\v3_0\Auth\LoginRequest;
use App\Exceptions\AuthenticationFailedException;
use App\Http\Resources\v3_0\UserSensitiveResource;

/**
 * @OA\Post(
 *     path="/{lang}/v3.0/auth/login",
 *     tags={"Auth3.0"},
 *     operationId="login",
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
 *                  required={"email", "password"},
 *                  @OA\Property(
 *                      property="email",
 *                      type="string"
 *                  ),
 *                  @OA\Property(
 *                      property="password",
 *                      type="string"
 *                  ),
 *              )
 *          )
 *     ),
 *     security={{ "bearerAuth": {} }},
 *     @OA\Response(
 *          response=200,
 *          description="successful operation",
 *          @OA\JsonContent(type="object",
 *               @OA\Property(property="resource_type", type="string"),
 *               @OA\Property(property="id", type="integer"),
 *               @OA\Property(property="name", type="string"),
 *               @OA\Property(property="language", type="string"),
 *               @OA\Property(property="avatar_url", type="string"),
 *               @OA\Property(property="subscription_type", type="string"),
 *               @OA\Property(property="subscription_expires_at", format="date-time"),
 *               @OA\Property(property="content", type="array", @OA\Items()),
 *               @OA\Property(property="token_type", type="string"),
 *               @OA\Property(property="access_token", type="string"),
 *               @OA\Property(property="session_expires_at", format="date-time"),
 *          ),
 *       ),
 *       @OA\Response(response=401, description="Unauthorized"),
 * )
 */
class LoginController extends Controller
{
    /** @var LanguageDatabaseService */
    protected $languageDatabaseService;

    public function __construct(LanguageDatabaseService $languageDatabaseService)
    {
        $this->languageDatabaseService = $languageDatabaseService;
    }

    public function __invoke(LoginRequest $request)
    {
        $user = $this->authenticate($request->credentials());
        $personalAccessToken = $user->createToken('Personal Access Token');

        UserLoggedInEvent::dispatch($user);

        $user->load([
            'content.recipe',
            'content.remedy',
            'remedies.bodySystems',
            'favourites',
        ]);

        return UserSensitiveResource::make($user, $personalAccessToken);
    }

    protected function authenticate(array $credentials): User
    {
        if (Auth::attempt($credentials)) {
            return Auth::user();
        }

        if ($this->attemptAuthAgainstAllLanguageDatabases($credentials)) {
            return Auth::user();
        }

        throw new AuthenticationFailedException();
    }

    protected function attemptAuthAgainstAllLanguageDatabases(array $credentials): bool
    {
        $authenticated = false;
        $foundInLanguage = $this->languageDatabaseService->currentLanguage();

        $this->languageDatabaseService->eachDatabase(function(UserLanguage $language) use ($credentials, &$authenticated, &$foundInLanguage) {
            if (Auth::attempt($credentials)) {
                $authenticated = true;
                $foundInLanguage = $language;

                return true;
            }
        });

        if ($authenticated) {
            $this->languageDatabaseService->setLanguage($foundInLanguage);
        }

        return $authenticated;
    }
}
