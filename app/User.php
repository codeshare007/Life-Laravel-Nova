<?php

namespace App;

use App\Enums\Region;
use App\Enums\Platform;
use App\Enums\UserLanguage;
use Illuminate\Support\Carbon;
use App\Enums\SubscriptionType;
use Laravel\Passport\HasApiTokens;
use BenSampo\Enum\Traits\CastsEnums;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Notifications\Notifiable;
use App\Services\LanguageDatabaseService;
use App\Services\Subscriptions\Subscription;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Services\Subscriptions\Contracts\SubscriptionModelContract;

/**
 * @property Platform|null $platform
 * @property SubscriptionType|null $subscription_type
 * @property UserLanguage $language
 */
class User extends Authenticatable
{
    use Notifiable;
    use HasApiTokens;
    use CanResetPassword;
    use CastsEnums;

    protected $guarded = [
        'id',
        'is_admin',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $with = [
        'avatar',
    ];

    protected $casts = [
        'bypass_subscription_receipt_validation' => 'boolean',
        'settings' => 'array',
        'subscription_type' => 'int',
        'subscription_expires_at' => 'datetime',
        'last_logged_in_at' => 'datetime',
        'last_used_app_at' => 'datetime',
    ];

    protected $enumCasts = [
        'platform' => Platform::class,
        'subscription_type' => SubscriptionType::class,
    ];

    public static function boot()
    {
        parent::boot();

        static::deleting(function (User $user) {
            $user->recipes->each->anonymise();
            $user->remedies->each->anonymise();
        });
    }

    public function favourites()
    {
        return $this->hasMany(Favourite::class)->with('favouriteable');
    }

    public function collections()
    {
        return $this->hasMany(Collection::class);
    }

    public function avatar()
    {
        return $this->belongsTo(Avatar::class);
    }

    public function notificationSettings()
    {
        return $this->hasOne(NotificationSettings::class);
    }

    public function content()
    {
        return $this->hasMany(UserGeneratedContent::class);
    }

    public function recipes()
    {
        return $this->hasMany(Recipe::class);
    }

    public function remedies()
    {
        return $this->hasMany(Remedy::class);
    }

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class);
    }

    public function hasActiveSubscription(string $receipt, Platform $platform)
    {
        if ($this->bypass_subscription_receipt_validation) {
            // Subscription type and expires must be set
            if ($this->subscription_type && $this->subscription_expires_at) {
                if ($this->subscription_expires_at->isFuture()) {
                    \Log::info('Bypassing receipt validation for: ' . $this->email);

                    return true;
                } else {
                    $this->update([
                        'bypass_subscription_receipt_validation' => false,
                    ]);
                }
            }
        }

        \Log::info('Checking receipt for: ' . $this->email);
        \Log::info('Receipt (' . $platform->key . '): ' . $receipt);

        try {
            $subscription = Subscription::get($receipt, $platform);

            if ($subscription->isActive) {
                $this->updateSubscription($subscription);
                $this->save();

                \Log::info('Receipt verified. Expires at: ' . $this->subscription_expires_at . ' Type: ' . $this->subscription_type->key);

                return true;
            }
        } catch (\Exception $e) {
            \Log::info('Receipt verification failed - ' . $e->getMessage());
        }

        \Log::info('Receipt failed verification');

        return false;
    }

    public function avatarUrl(): string
    {
        if ($this->avatar_url) {
            return Storage::url($this->avatar_url);
        } elseif ($this->avatar) {
            return Storage::url($this->avatar->image_url);
        }

        return '';
    }

    public function updateAvatar(string $base64)
    {
        try {
            $image = Image::make($base64)->encode('jpg');
            $hash = md5($this->id . now()->timestamp);
            $imagePath = 'user-avatars/' . $hash . '.jpeg';

            if (Storage::put($imagePath, $image)) {
                $this->update([
                    'avatar_url' => $imagePath
                ]);
            }
        } catch (\Throwable $th) {
        }
    }

    public function scopeOnActiveTrial()
    {
        return $this
            ->whereSubscriptionType(SubscriptionType::TheEssentialLifeMembershipTrial)
            ->where('subscription_expires_at', '>=', now());
    }

    public function scopeOnInactiveTrial()
    {
        return $this
            ->whereSubscriptionType(SubscriptionType::TheEssentialLifeMembershipTrial)
            ->where('subscription_expires_at', '<', now());
    }

    public function isDevTest(): bool
    {
        return ends_with($this->email, '@apple-wqa-dev.test');
    }

    public function updateSubscription(SubscriptionModelContract $subscription): self
    {
        $this->subscription_expires_at = $subscription->expiration;
        $this->subscription_type = $subscription->type;
        $this->platform = $subscription->platform;

        $this->save();

        return $this;
    }

    public function getLanguageAttribute(): UserLanguage
    {
        return (new LanguageDatabaseService())->languageFromConnectionName($this->connection);
    }

    public function getFirebaseIdAttribute(): string
    {
        return $this->language->value . '_' . $this->id;
    }

    public static function inAnyDbFindBy($columnName, $value): ?User
    {
        $user = null;

        (new LanguageDatabaseService())->eachDatabase(function (UserLanguage $language) use ($columnName, $value, &$user) {
            $user = Self::where($columnName, $value)->first();

            if ($user) {
                return true;
            }
        });

        return $user;
    }

    public function getSubscriptionExpiresAtAttribute($value): Carbon
    {
        $value = $this->castAttribute('subscription_expires_at', $value);

        return $value ? Carbon::createFromTimeString($value) : Carbon::createFromTimestamp(0);
    }

    public function enableBypass(Carbon $expiryDate = null): void
    {
        $this->subscription_expires_at = $expiryDate ?? now()->addYear();
        $this->bypass_subscription_receipt_validation = true;
        $this->subscription_type = SubscriptionType::None();

        $this->save();
    }

    public function disableBypass(): void
    {
        $this->bypass_subscription_receipt_validation = false;
        $this->subscription_type = SubscriptionType::None();
        $this->subscription_expires_at = now();

        $this->save();
    }

    public function region(): Region
    {
        return Region::coerce($this->region_id) ?? Region::US();
    }

    public function klaviyoProfile(): array
    {
        return [
            'name' => $this->name,
            'essential_life_account_created_at' => $this->created_at ? $this->created_at->toDateTimeString() : "",
            'language' => $this->language->key,
            'region' => $this->region()->key,
            'subscription_type' => $this->subscription_type ? $this->subscription_type->key : SubscriptionType::None()->key,
            'subscription_expires_at' => $this->subscription_expires_at ? $this->subscription_expires_at->toDateTimeString() : "",
            'last_logged_in_at' => $this->last_logged_in_at ? $this->last_logged_in_at->toDateTimeString() : "",
            'last_used_app_at' => $this->last_used_app_at ? $this->last_used_app_at->toDateTimeString() : "",
            'app_version' => $this->app_version ? $this->app_version : "",
            'app_build_number' => $this->app_build_number ? $this->app_build_number : "",
            'system_version' => $this->system_version ? $this->system_version : "",
            'device_name' => $this->device_name ? $this->device_name : "",
            'platform' => $this->platform ? $this->platform->key : "",
        ];
    }
}
