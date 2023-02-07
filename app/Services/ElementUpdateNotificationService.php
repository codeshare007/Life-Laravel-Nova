<?php

namespace App\Services;

use App\Enums\PushNotificationType;
use App\User;
use LaravelFCM\Facades\FCM;
use App\UserGeneratedContent;
use LaravelFCM\Message\Topics;
use Illuminate\Support\Facades\Log;
use LaravelFCM\Message\OptionsBuilder;
use App\Enums\UserGeneratedContentStatus;
use LaravelFCM\Message\PayloadDataBuilder;
use App\Exceptions\FirebaseNotificationException;
use LaravelFCM\Message\PayloadNotificationBuilder;

class ElementUpdateNotificationService
{
    public static function sendUuidUpdates($uuids)
    {
        if (app()->environment('testing')) {
            return;
        }

        $dataBuilder = new PayloadDataBuilder();
        $dataBuilder->addData([
            'notification_type' => PushNotificationType::Element,
            'elements' => implode(',', $uuids)
        ]);
        $data = $dataBuilder->build();
        $topic = new Topics();
        $topic->topic('global');
        $topicResponse = FCM::sendToTopic($topic, null, null, $data);
        if ($topicResponse->error()) {
            throw new FirebaseNotificationException('Failed to notify FCM');
        }
    }

    public static function sendUGCRejected(UserGeneratedContent $ugc)
    {
        $payload = [
            'notification_type' => PushNotificationType::UgcStatus,
            'uuid' => $ugc->uuid,
            'public_uuid' => $ugc->publicModel ? $ugc->publicModel->uuid : null,
            'status' => UserGeneratedContentStatus::getKey($ugc->status),
            'reasonSubject' => $ugc->rejection_reason_subject,
            'reasonBody' => $ugc->rejection_reason_description,
        ];

        $type = strtolower($ugc->type);

        self::notifyUser($ugc->user, $ugc->name, "You have an update regarding your $type.", $payload);
    }

    public static function sendUGCApproved(UserGeneratedContent $ugc)
    {
        $payload = [
            'notification_type' => PushNotificationType::UgcStatus,
            'uuid' => $ugc->uuid,
            'public_uuid' => $ugc->publicModel ? $ugc->publicModel->uuid : null,
            'status' => UserGeneratedContentStatus::getKey($ugc->status),
        ];

        $type = strtolower($ugc->type);

        self::notifyUser($ugc->user, $ugc->name, "Good news! Your $type has been approved.", $payload);
    }

    protected static function notifyUser(User $user, string $subject, string $body, array $payload = [])
    {
        if (app()->environment('testing')) {
            return;
        }

        if (!$user->fcm_token) {
            Log::error('User fcm token doesn\'t exist for ' . $user->email . ' id: ' .  $user->id);
            return;
        }

        $options = (new OptionsBuilder())->setContentAvailable(true)->setPriority('high')->build();
        $notification = (new PayloadNotificationBuilder($subject))->setBody($body)->setSound('default')->setBadge(1)->build();
        $data = (new PayloadDataBuilder())->addData($payload)->build();

        /** @var DownstreamResponse $downstreamResponse */
        $downstreamResponse = FCM::sendTo($user->fcm_token, $options, $notification, $data);

        if ($downstreamResponse->numberFailure()) {
            throw new FirebaseNotificationException('User is in offline mode and Firebase has not yet regenerated the token. Please try again soon when the user may have re-established a connection.');
        }
    }
}
