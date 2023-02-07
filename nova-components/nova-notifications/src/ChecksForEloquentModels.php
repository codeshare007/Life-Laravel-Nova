<?php

namespace Christophrumpel\NovaNotifications;

use ReflectionClass;
use ReflectionException;

trait ChecksForEloquentModels {

    protected function isEloquentModel(string $className)
    {
        try {
            $classInfo = new ReflectionClass($className);
        } catch (ReflectionException $e) {
            return false;
        }

        return $classInfo->isSubclassOf('Illuminate\Database\Eloquent\Model');
    }

}
