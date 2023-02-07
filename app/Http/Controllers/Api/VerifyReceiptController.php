<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Enums\Platform;
use App\Services\Subscriptions\Subscription;

class VerifyReceiptController
{
    public function verify(Request $request)
    {
        $request->validate([
            'receipt' => 'required|string',
        ]);

        $platform = $request->platform == null ? Platform::iOS() : Platform::getInstance($request->platform);

        \Log::info('Returning raw receipt data (verify-receipt endpoint) (' . $platform->key . '): ' . $request->receipt);

        return (array) Subscription::get($request->receipt, $platform)->rawData;
    }
}
