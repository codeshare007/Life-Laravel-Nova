<?php

namespace App\Services\Subscriptions\iOS;

use Exception;
use GuzzleHttp\Client;
use App\Services\Subscriptions\BasePlatform;
use App\Services\Subscriptions\iOS\iOSSubscription;
use App\Services\Subscriptions\Contracts\SubscriptionModelContract;

class iOSPlatform extends BasePlatform
{
    /** @var Client */
    protected $appleClient;

    /** @var Client */
    protected $appleSandboxClient;

    const RECEIPT_IS_FROM_THE_TEST_ENVIRONMENT_BUT_IT_WAS_SENT_TO_THE_PRODUCTION_ENVIRONMENT = 21007;

    public function __construct()
    {
        if (! config('services.apple.key')) {
            throw new Exception('Missing Apple API password. Add it to environment variables with the key APPLE_API_PASSWORD');
        }

        $this->appleClient = new Client([
            'base_uri' => 'https://buy.itunes.apple.com',
        ]);

        $this->appleSandboxClient = new Client([
            'base_uri' => 'https://sandbox.itunes.apple.com',
        ]);
    }

    public function get(string $receipt): SubscriptionModelContract
    {
        $subscription = new iOSSubscription();

        if ($receipt === static::BYPASS_TOKEN) {
            return $subscription->applyBypass();
        }

        return $subscription->applyReceiptData($this->getReceiptData($receipt));
    }

    protected function getReceiptData(string $receipt): object
    {
        $payload = [
            'json' => [
                'receipt-data' => $receipt,
                'password' => config('services.apple.key'),
            ],
        ];

        $response = $this->appleClient->post('verifyReceipt', $payload);

        if ($response->getStatusCode() !== 200) {
            throw new Exception('Apple receipt verification call failed with the status code ' . $response->getStatusCode());
        }

        $responseBody = json_decode((string) $response->getBody());

        if ($responseBody->status === static::RECEIPT_IS_FROM_THE_TEST_ENVIRONMENT_BUT_IT_WAS_SENT_TO_THE_PRODUCTION_ENVIRONMENT) {
            $response = $this->appleSandboxClient->post('verifyReceipt', $payload);
            $responseBody = json_decode((string) $response->getBody());
        }

        return $responseBody;
    }
}
