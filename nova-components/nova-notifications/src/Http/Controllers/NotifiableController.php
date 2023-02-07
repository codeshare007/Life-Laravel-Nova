<?php

namespace Christophrumpel\NovaNotifications\Http\Controllers;

use ReflectionClass;
use Christophrumpel\NovaNotifications\ClassFinder;

class NotifiableController extends ApiController
{
    /**
     * @var ClassFinder
     */
    private $classFinder;

    /**
     * NotifiableController constructor.
     *
     * @param ClassFinder $classFinder
     */
    public function __construct(ClassFinder $classFinder)
    {
        $this->classFinder = $classFinder;
    }

    public function index()
    {
        return $this->classFinder->findByExtending('Illuminate\Database\Eloquent\Model', config('nova-notifications.notifiableNamespaces'))
            ->filter(function ($className) {
                $classInfo = new ReflectionClass($className);

                return in_array('Illuminate\Notifications\Notifiable', $classInfo->getTraitNames());
            })
            ->map(function ($className) {
                return [
                    'className' => $className,
                    'name' => $this->titleCase(class_basename($className)),
                    'options' => $className::select(['id', 'name'])->get(),
                ];
            })->values();
    }

    /**
     * Transform the value into title case
     *
     * @param string $value
     * @return string
     */
    protected function titleCase(string $value): string
    {
        if (ctype_upper(str_replace('_', '', $value))) {
            $value = strtolower($value);
        }

        return title_case(str_replace('_', ' ', snake_case($value)));
    }
}
