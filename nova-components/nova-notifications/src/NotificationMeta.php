<?php

namespace Christophrumpel\NovaNotifications;

use ReflectionClass;
use ReflectionMethod;
use ReflectionException;
use ReflectionParameter;
use Laravel\Nova\Fields\Text;
use Illuminate\Support\Carbon;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\DateTime;
use Illuminate\Support\Collection;
use Laravel\Nova\Fields\BelongsTo;
use Illuminate\Contracts\Support\Arrayable;
use Christophrumpel\NovaNotifications\ChecksForEloquentModels;
use Christophrumpel\NovaNotifications\Contracts\HasCustomFields;

class NotificationMeta implements Arrayable
{
    use ChecksForEloquentModels;
    
    protected $className;

    protected $parameters = [];

    public function __construct(string $className) 
    {
        $this->className = $className;
    }
    
    /**
     * Return the notifcation meta as an array
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'className' => $this->className,
            'name' => $this->getName(),
            'parameters' => $this->getParameters(),
            'fields' => $this->getFields(),
        ];
    }

    /**
     * Get the friendly name for the Notification
     *
     * @return string
     */
    protected function getName(): string
    {
        return $this->titleCase(class_basename($this->className));
    }

    /**
     * Get the friendly name for a Parameter
     *
     * @param ReflectionParameter $param
     * @return string
     */
    protected function getParameterDisplayName(ReflectionParameter $param): string
    {
        return $this->titleCase($param->getName());
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

    /**
     * Get the parameters for the notification using reflection
     *
     * @return Collection
     */
    public function getParameters(): Collection
    {
        try {
            $classInfo = new ReflectionMethod($this->className, '__construct');

            $this->parameters = collect($classInfo->getParameters())->map(function (ReflectionParameter $param) {
                return [
                    'name' => $param->getName(),
                    'displayName' => $this->getParameterDisplayName($param),
                    'type' => $this->getParameterType($param),
                ];
            });

            return $this->parameters;

        } catch (ReflectionException $e) {
            return [];
        }
    }

    /**
     * Get the parameter type
     *
     * @param ReflectionParameter $param
     * @return string
     */
    protected function getParameterType(ReflectionParameter $param): string
    {
        return is_null($param->getType()) ? 'unknown' : $param->getType()->getName();
    }

    /**
     * Get the fields for the notification parameters
     *
     * @return Collection
     */
    public function getFields(): Collection
    {
        if ($this->hasCustomFields()) {
            return collect($this->className::fields());
        }

        return $this->guessFields();
    }

    /**
     * Guess fields based on the notification parameters
     *
     * @return Collection
     */
    protected function guessFields(): Collection
    {
        if (count($this->parameters) === 0) {
            $this->parameters = $this->getParameters();
        }

        return $this->parameters->map(function(array $parameter) {

            $type = $parameter['type'];
            $label = $parameter['displayName'];
            $name = $parameter['name'];

            if ($this->isEloquentModel($type)) {
                $options = $type::all()->keyBy('id')->map(function ($item) {
                    return $item->name ?? $item->id;
                });

                return Select::make($label, $name)->options($options); 
            }

            if ($type === Carbon::class) {
                return DateTime::make($label, $name);
            }

            return Text::make($label, $name);
        });
    }

    /**
     * Check to see if the notification has any custom fields
     *
     * @return boolean
     */
    protected function hasCustomFields(): bool
    {
        return (new ReflectionClass($this->className))->implementsInterface(HasCustomFields::class);
    }
}