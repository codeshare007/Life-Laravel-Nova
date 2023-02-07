<?php

namespace Christophrumpel\NovaNotifications\Http\Controllers;

use Christophrumpel\NovaNotifications\NotificationResolver;

class NotificationClassesController extends ApiController
{
    public function index(NotificationResolver $notificationResolver)
    {
        return $notificationResolver->getAll();
    }
}
