<?php

namespace App\Services\Subscriptions\Android;

use Exception;
use Google_Client;
use Google_Service_AndroidPublisher;
use App\Services\Subscriptions\BasePlatform;
use Google_Service_AndroidPublisher_SubscriptionPurchase;
use App\Services\Subscriptions\Android\AndroidSubscription;
use App\Services\Subscriptions\Contracts\SubscriptionModelContract;

class AndroidPlatform extends BasePlatform
{
    /** @var Google_Service_AndroidPublisher */
    protected $androidClient;
    
    const PACKAGE_NAME = 'com.totalwellnessmedia.essentiallife';
    const SUBSCRIPTION_ID = 'subscription_1';
    const AUTH_CONFIG_FILE = 'Google Play Android Developer-8525cfb15b0d.json';
    const APPLICATION_NAME = 'Essential_Life_Api';

    public function __construct()
    {
        $this->androidClient = $this->getAndroidClient();
    }

    public function get(string $receipt): SubscriptionModelContract
    {
        $subscription = new AndroidSubscription();

        if ($receipt === static::BYPASS_TOKEN) {
            return $subscription->applyBypass();
        }

        return $subscription->applyReceiptData($this->getReceiptData($receipt));
    }

    protected function getReceiptData(string $receipt): ?Google_Service_AndroidPublisher_SubscriptionPurchase
    {
        try {
            return $this->androidClient->purchases_subscriptions->get(static::PACKAGE_NAME, static::SUBSCRIPTION_ID, $receipt);
        } catch (Exception $e) {
            return null;
        }
    }

    protected function getAndroidClient(): Google_Service_AndroidPublisher
    {
        $authConfigFile = storage_path(static::AUTH_CONFIG_FILE);

        if (! file_exists($authConfigFile)) {
            throw new Exception('Missing Google API credentials for Android subscription verification. Add the json file to ' . $authConfigFile);
        }

        $googleClient = new Google_Client();
        $googleClient->setApplicationName(static::APPLICATION_NAME);
        $googleClient->setAuthConfig($authConfigFile);
        $googleClient->addScope(Google_Service_AndroidPublisher::ANDROIDPUBLISHER);
    
        return new Google_Service_AndroidPublisher($googleClient);
    }
}
