<?php

namespace App\Http\Controllers\Api\v3_0\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Services\Subscriptions\Subscription;
use App\Exceptions\InactiveSubscriptionException;
use App\Http\Requests\v3_0\Auth\SignupRequest;

/**
 * @OA\Post(
 *     path="/{lang}/v3.0/auth/signup",
 *     tags={"Auth3.0"},
 *     operationId="signup",
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
 *                  required={"email", "password", "name", "receipt"},
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
  *                  @OA\Property(
 *                      property="receipt",
 *                      type="string"
 *                  ),
 *                  @OA\Property(
 *                      property="platform",
 *                      type="string",
 *                  ),
 *              )
 *          )
 *     ),
 *     security={{ "bearerAuth": {} }},
 *     @OA\Response(
 *          response=200,
 *          description="Successfully created user!",
 *     ),
 * )
 */
class SignUpController extends Controller
{
    public function __invoke(SignupRequest $request)
    {
        $subscription = Subscription::get($request->receipt, $request->platform);

        if (! $subscription->isActive) {
            throw new InactiveSubscriptionException();
        }
        
        User::create([
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'name' => $request->name,
            'avatar_id' => $request->avatar_id,
            'subscription_expires_at' => $subscription->expiration,
            'subscription_type' => $subscription->type,
            'platform' => $subscription->platform,
            'bypass_subscription_receipt_validation' => $request->containsDevEmail(),
        ]);

        return response()->json([
            'message' => 'Successfully created user!'
        ], 201);
    }
}
