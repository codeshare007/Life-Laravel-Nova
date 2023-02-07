<?php

namespace Christophrumpel\NovaNotifications\Http\Controllers;

use ReflectionMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Laravel\Nova\Http\Requests\NovaRequest;
use Illuminate\Support\Facades\Notification;
use Christophrumpel\NovaNotifications\NotificationResolver;
use Christophrumpel\NovaNotifications\ChecksForEloquentModels;

class NotificationController extends ApiController
{
    use ChecksForEloquentModels;

    protected $notificationResolver;

    public function __construct(NotificationResolver $notificationResolver)
    {
        $this->notificationResolver = $notificationResolver;
    }
    
    public function index()
    {
        return DB::table('nova_notifications')
            ->get();
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function send(Request $request)
    {
        $notificationClass = $request->input('notification.className');

        if (! class_exists($notificationClass)) {
            throw new \Exception('Unknown notification class');
        }

        $requestParameters = collect($request->input('notification.parameters'));

        $this->validateForSend($requestParameters, $notificationClass);

        $notificationParameterValues = $this->getParameterValuesForNotification($requestParameters, $notificationClass);

        $notification = $notificationParameterValues ? new $notificationClass(...$notificationParameterValues) : new $notificationClass();

        $notifiable = $request->input('notifiable.className');

        if (in_array($request->input('notifiable.sendToAll'), [true, 'true'], true)) {

            $notifiable::chunk(50, function($notifiables) use($notification) {
                Notification::send($notifiables, $notification);
            });

        } else {
            Notification::send($notifiable::findOrFail($request->input('notifiable.value')), $notification);
        }

        $this->respondSuccess();
    }

    protected function validateForSend(Collection $requestParameters, string $className)
    {
        $fields = $this->notificationResolver->getByClassName($className)->getFields();

        $requestParameters = $requestParameters->mapWithKeys(function($param) {
            return $param;
        })->all();

        $rules = $fields->mapWithKeys(function ($field) {
            return $field->getRules(new NovaRequest());
        })->all();

        Validator::make($requestParameters, $rules)->validate();
    }

    protected function getParameterValuesForNotification(Collection $requestParameters, string $className): array
    {
        $notificationClassParameters = $this->notificationResolver->getByClassName($className)->getParameters();

        return $requestParameters->map(function(array $requestParameter) use ($notificationClassParameters) {
            $requestParameterName = key($requestParameter);
            $requestParameterValue = $requestParameter[$requestParameterName];
            $notificationClassParameter = $notificationClassParameters->where('name', $requestParameterName)->first();
            $parameterType = $notificationClassParameter['type'];

            if ($this->isEloquentModel($parameterType)) {
                return $parameterType::findOrFail($requestParameterValue);
            }

            if ($parameterType === Carbon::class) {
                return $requestParameterValue === null ? null : Carbon::parse($requestParameterValue);
            }

            return $requestParameterValue;

        })->flatten()->all();
    }
}
