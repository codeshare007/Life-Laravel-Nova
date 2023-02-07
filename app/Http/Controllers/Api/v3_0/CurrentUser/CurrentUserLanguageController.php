<?php

namespace App\Http\Controllers\Api\v3_0\CurrentUser;

use App\User;
use App\Favourite;
use Carbon\Carbon;
use App\Enums\UserLanguage;
use Illuminate\Http\Request;
use App\UserGeneratedContent;
use BenSampo\Enum\Rules\EnumValue;
use Illuminate\Support\Facades\DB;
use App\Events\UserSwitchedLanguage;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Services\LanguageDatabaseService;
use Illuminate\Support\Facades\Validator;

class CurrentUserLanguageController extends Controller
{
    protected $languageDatabaseService;

    public function __construct(LanguageDatabaseService $languageDatabaseService)
    {
        $this->languageDatabaseService = $languageDatabaseService;
    }

    public function __invoke(Request $request, $lang)
    {
        $validator = Validator::make($request->all(), [
            'language' => ['required', new EnumValue(UserLanguage::class)]
        ]);

        if ($validator->fails()) {
            return response()->json(['Unknown language selection'], 422);
        }

        if ($lang == $request->get('language')) {
            return response()->json(['User already set to this language'], 200);
        }

        // Get existing favourites and using the uuid, carry them over to new
        // database. Any UGC favourites, create private recipes/remedies.
        $user = Auth::user();

        $allFavourites = $user->favourites;
        $userUgc = $user->content;

        [$ugcFavourites, $appFavourites] = $user->favourites
            ->pluck('favouriteable')
            ->partition(function ($favourite) {
                if ($favourite) {
                    return $favourite->user_id;
                }
            });
            
        // anonymising UGC is taken care of in the
        // deleting event on the User model
        Auth::user()->delete();
        
        // create new user and attach data
        $fromLanguage = $this->languageDatabaseService->currentLanguage();
        $toLanguage = UserLanguage::coerce($request->language);
        $this->languageDatabaseService->setLanguage($toLanguage);

        $userArray = [
            'is_admin' => $user['is_admin'],
            'region_id' => $user['region_id'],
            'avatar_id' => $user['avatar_id'],
            'avatar_url' => $user['avatar_url'],
            'email' => $user['email'],
            'password' => $user['password'],
            'name' => $user['name'],
            'bio' => $user['bio'],
            'subscription_expires_at' => $user['subscription_expires_at'],
            'remember_token' => $user['remember_token'],
            'subscription_type' => $user['subscription_type'],
            'bypass_subscription_receipt_validation' => $user['bypass_subscription_receipt_validation'],
        ];

        if (!DB::table('users')->insert($userArray)) {
            return response()->json(['message' => 'failed to create new user!']);
        }

        $newUser = User::where('email', $user['email'])->first();

        $userUgc->where('is_public', 0)
            ->each(function ($content) use ($newUser) {
                UserGeneratedContent::create([
                    'name' => $content['name'],
                    'type' => $content['type'],
                    'content' => json_encode($content['content']),
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                    'association_id' => $content['association_id'],
                    'status' => $content['status'],
                    'is_public' => 0,
                    'user_id' => $newUser->id,
                    "rejection_reason_subject" => $content['rejection_reason_subject'],
                    "image_url" => $content['image_url'],
                    "rejection_reason_description" => $content['rejection_reason_description'],
                ]);
            });

        // add standard favourites
        $createFavourites = $appFavourites->map(function ($favourite) use ($newUser) {
            if ($favourite) {
                return [
                    'favouriteable_type' => get_class($favourite),
                    'favouriteable_id' => $favourite->id,
                    'user_id' => $newUser->id,
                    'updated_at' => Carbon::now(),
                    'created_at' => Carbon::now(),
                ];
            }
        })->all();
        Favourite::insert($createFavourites);

        UserSwitchedLanguage::dispatch($newUser, $fromLanguage, $toLanguage);

        return response(null, 200);
    }
}
