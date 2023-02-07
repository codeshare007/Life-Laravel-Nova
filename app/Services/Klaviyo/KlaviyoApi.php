<?php

namespace App\Services\Klaviyo;

use Exception;
use GuzzleHttp\Client;

class KlaviyoApi
{
    protected $client;

    public function __construct()
    {
        if (! config('services.klaviyo.public_key')) {
            throw new Exception('Missing Klaviyo public key. Add it to environment variables with the key KLAVIYO_PUBLIC_KEY');
        }

        $this->client = new Client([
            'base_uri' => 'https://a.klaviyo.com/api/',
            'headers' => [
                'Accept' => 'text/html',
                'Content-Type' => 'application/x-www-form-urlencoded',
            ],
        ]);
    }

    public function identifyProfile(string $email, array $properties): bool
    {
        $data = [
            "token" => config('services.klaviyo.public_key'),
            "properties" => array_merge($properties, [
                '$email' => $email,
            ]),
        ];

        $response = $this->client->post('identify', [
            'form_params' => [
                'data' => json_encode($data),
            ],
        ]);

        return ((string) $response->getBody() === "1");
    }

    public function trackProfileActivity(string $email, string $eventName, array $eventProperties): bool
    {
        $data = [
            "token" => config('services.klaviyo.public_key'),
            "customer_properties" => [
                '$email' => $email,
            ],
            "event" => $eventName,
            "properties" => $eventProperties,
        ];

        $response = $this->client->post('track', [
            'form_params' => [
                'data' => json_encode($data),
            ],
        ]);

        return ((string) $response->getBody() === "1");
    }
}
