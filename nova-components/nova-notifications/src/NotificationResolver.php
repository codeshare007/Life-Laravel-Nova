<?php

namespace Christophrumpel\NovaNotifications;

use ReflectionClass;
use Illuminate\Support\Collection;
use Christophrumpel\NovaNotifications\NotificationMeta;

class NotificationResolver
{
    /**
     * @var ClassFinder
     */
    protected $classFinder;

    /**
     * NotificationClassesController constructor.
     *
     * @param ClassFinder $classFinder
     */
    public function __construct(ClassFinder $classFinder)
    {
        $this->classFinder = $classFinder;
    }

    /**
     * Get and return all of the notification classes
     *
     * @return Collection
     */
    public function getAll(): Collection
    {
        return $this->findNotificationClasses()->map(function ($className) {
            return $this->getByClassName($className);
        })->filter()->values();
    }

    /**
     * Find all classes which extend the base Notification class within the given namespaces.
     *
     * @return Collection
     */
    public function findNotificationClasses(): Collection
    {
        $namespaces = config('nova-notifications.notificationNamespaces');

        return $this->classFinder->findByExtending('Illuminate\Notifications\Notification', $namespaces);
    }
    
    /**
     * Get the details for a single Notification by class name
     *
     * @param string $className
     * @return NotificationMeta|null
     */
    public function getByClassName(string $className): ?NotificationMeta
    {
        if ($this->classIsAbstract($className)) {
            return null;
        }

        return (new NotificationMeta($className));
    }

    /**
     * Returns whether of not the class is abstract
     *
     * @param string $className
     * @return boolean
     */
    protected function classIsAbstract(string $className): bool
    {
        return (new ReflectionClass($className))->isAbstract();
    }
}
