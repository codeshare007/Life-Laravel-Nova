<?php

namespace Christophrumpel\NovaNotifications\Http\Controllers;

use ReflectionClass;
use Illuminate\Http\Response;

abstract class ApiController
{
    public function respondSuccess(): Response
    {
        return response('', Response::HTTP_NO_CONTENT);
    }
}
