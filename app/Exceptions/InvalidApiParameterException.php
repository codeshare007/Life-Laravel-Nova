<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Support\Facades\Response;

class InvalidApiParameterException extends Exception
{
    private $validationErrors;

    public function __construct($validationErrors) {
        $this->validationErrors = $validationErrors;
    }

    public function render($request)
    {
        return Response::json([
            'message' => 'There were 1 or more errors with the supplied URL parameters.',
            'errors' => $this->validationErrors,
        ], \Illuminate\Http\Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
