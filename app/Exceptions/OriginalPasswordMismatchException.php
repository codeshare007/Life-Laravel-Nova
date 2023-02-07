<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Support\Facades\Response;

class OriginalPasswordMismatchException extends Exception
{
    public function render()
    {
        return Response::json([
            'message' => 'The supplied original password does not match the users original password.',
        ], \Illuminate\Http\Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
