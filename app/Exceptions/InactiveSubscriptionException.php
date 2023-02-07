<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class InactiveSubscriptionException extends Exception
{
    public function render(Request $request)
    {
        return Response::json([
            'message' => 'Unauthorized. User does not have an active subscription.',
        ], \Illuminate\Http\Response::HTTP_PAYMENT_REQUIRED);
    }
}
