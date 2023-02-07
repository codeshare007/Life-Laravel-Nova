<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Support\Facades\Response;

class AuthenticationFailedException extends Exception
{
    public function report()
    {
        
    }

    public function render()
    {
        return Response::json([
            'message' => 'Unauthorized',
        ], \Illuminate\Http\Response::HTTP_UNAUTHORIZED);
    }
}
