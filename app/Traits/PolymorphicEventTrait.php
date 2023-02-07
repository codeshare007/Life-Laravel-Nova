<?php

namespace App\Traits;

use App\Enums\ElementRelationshipType;
use App\Exceptions\FirebaseNotificationException;
use App\Services\ElementUpdateNotificationService;
use stringEncode\Exception;

trait PolymorphicEventTrait
{
    public static function bootPolymorphicEventTrait()
    {
        static::created(function ($model) {
            $uuids = self::getUUIDs($model);

            try {
                ElementUpdateNotificationService::sendUuidUpdates($uuids);
            } catch (FirebaseNotificationException $e) {
                throw new Exception('Failed to send FCM');
            }
        });

        static::deleted(function ($model) {
            $uuids = self::getUUIDs($model);

            try {
                ElementUpdateNotificationService::sendUuidUpdates($uuids);
            } catch (FirebaseNotificationException $e) {
                throw new Exception('Failed to send FCM');
            }
        });
    }

    private static function getUUIDs($model)
    {
        $uuids = [];
        switch ($model->fullModelName()) {
            case ElementRelationshipType::AilmentSolution:
            case ElementRelationshipType::AilmentSecondarySolution:
                $uuids[] = $model->ailment->uuid;
                $uuids[] = $model->solutionable->uuid;
                break;
            case ElementRelationshipType::RecipeIngredient:
                $uuids[] = $model->ingredientable->uuid;
                $uuids[] = $model->recipe->uuid;
                break;
            case ElementRelationshipType::SolutionGroup:
                $uuids[] = $model->useable->uuid;
                break;
            case ElementRelationshipType::SupplementIngredient:
                $uuids[] = $model->ingredientable->uuid;
                $uuids[] = $model->supplement->uuid;
                break;
            case ElementRelationshipType::Favourite:
                $uuids[] = $model->favouriteable->uuid;
                break;
        }

        return $uuids;
    }

}
