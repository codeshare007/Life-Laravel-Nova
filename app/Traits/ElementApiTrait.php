<?php

namespace App\Traits;

use App\Model;
use App\Recipe;
use App\Remedy;
use App\Element;
use App\DeletedElement;
use App\Enums\ApiVersion;
use App\Enums\ApiResource;
use Illuminate\Support\Str;
use stringEncode\Exception;
use App\Enums\ElementCacheKey;
use App\Events\ElementCreatedEvent;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Builder;
use App\Exceptions\MissingApiResourceReference;
use App\Exceptions\FirebaseNotificationException;
use App\Services\ElementUpdateNotificationService;
use Fico7489\Laravel\Pivot\Traits\PivotEventTrait;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait ElementApiTrait
{
    /** @var Model $this */

    use PivotEventTrait;
    use ElementDeleteTrait;

    private static function canPropageteElements($model)
    {
        $modelClass = get_class($model);
        if (in_array(get_class($model), Element::$ignorePropagationTypes)) {
            return false;
        }

        // don't propagate ugc recipes/remedies
        if (
            in_array($modelClass, [Remedy::class, Recipe::class]) &&
            $model->user_id > 0
        ) {
            return false;
        }

        return true;
    }

    public static function bootElementApiTrait()
    {
        static::created(function ($model) {
            $uuid = $model->uuid ?? Str::uuid();

            Element::create([
                'id' => $uuid,
                'element_type' => get_class($model),
                'element_id' => $model->id,
            ]);
            
            $model->uuid = $uuid;
            $model->save();

            if (self::canPropageteElements($model)) {
                event(new ElementCreatedEvent($model, $uuid));
            }

            Cache::forget($model->getApiCacheName());
        });

        static::deleting(function ($model) {
            $model->recordDeletion();

            Cache::forget($model->getApiCacheName());
        });

        static::updated(function ($model) {
            if ($model->isDirty()) {
                try {
                    ElementUpdateNotificationService::sendUuidUpdates([$model->uuid]);
                } catch (FirebaseNotificationException $e) {
                    throw new Exception('Failed to send FCM');
                }

                if (self::canPropageteElements($model)) {
                    foreach (ElementCacheKey::getValues() as $cache) {
                        Cache::forget($cache);
                    }
                }
            }
        });

        static::pivotAttached(function ($model, $relationName, $pivotIds, $pivotIdsAttributes) {
            self::sendRelationshipNotifications($model, $relationName, $pivotIds);
        });

        static::pivotDetached(function ($model, $relationName, $pivotIds) {
            self::sendRelationshipNotifications($model, $relationName, $pivotIds);
        });
    }

    private static function sendRelationshipNotifications($model, $relationName, $pivotIds)
    {
        $uuids = [];
        $uuids[] = $model->uuid;
        $className = get_class($model->{$relationName}()->getRelated());
        $relatedModelUuids = (new $className)->whereIn('id', $pivotIds)
            ->get()
            ->pluck('uuid')
            ->push($model->uuid)
            ->all();

        try {
            ElementUpdateNotificationService::sendUuidUpdates($relatedModelUuids);
        } catch (FirebaseNotificationException $e) {
            throw new Exception('Failed to send FCM');
        }

        Cache::forget($model->getApiCacheName());
    }

    /**
     * List of relationships we always
     * include when resolving the model
     * through the API
     *
     * @return array
     */
    abstract static function getDefaultIncludes(ApiVersion $version): array;

    /**
     * Fetch the resource class for the
     * element model
     *
     * @return int|string
     * @throws MissingApiResourceReference
     * @throws \ReflectionException
     */
    public function getApiResource(ApiVersion $version)
    {
        // dynamically load the resource name
        $shortName = (new \ReflectionClass(get_called_class()))->getShortName();

        if (!ApiResource::hasKey($shortName)) {
            throw new MissingApiResourceReference('Model reference is missing from ApiResource Enum');
        }

        return ApiResource::getValue($shortName);
    }

    /**
     * Get cache resource
     *
     * @return int|string
     * @throws MissingApiResourceReference
     * @throws \ReflectionException
     */
    public function getApiCacheName()
    {
        // dynamically load the resource name
        $shortName = (new \ReflectionClass(get_called_class()))->getShortName();

        if (!ElementCacheKey::hasKey($shortName)) {
            throw new MissingApiResourceReference('Model reference is missing from ElementCacheKey Enum');
        }

        return ElementCacheKey::getValue($shortName);
    }

    public static function findByIdOrUuid($id): ?self
    {
        if (isUuid($id)) {
            return static::where('uuid', $id)->first();
        }

        return static::find($id);
    }

    public function scopeWithDefaultIncludes(Builder $query, ApiVersion $version)
    {
        $query->with(self::getDefaultIncludes($version));
    }

    public function element(): BelongsTo
    {
        return $this->belongsTo(Element::class, 'uuid', 'id');
    }

    protected function recordDeletion()
    {
        DeletedElement::createFromModel($this);
    }
}
