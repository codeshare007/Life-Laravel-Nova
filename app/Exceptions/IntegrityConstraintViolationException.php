<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Support\Facades\Response;

class IntegrityConstraintViolationException extends Exception
{
    public function render($request)
    {
        \Log::error($this->getMessage());
        return Response::json([
            'message' => 'The entity could not be created because it already exists.',
        ], \Illuminate\Http\Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
