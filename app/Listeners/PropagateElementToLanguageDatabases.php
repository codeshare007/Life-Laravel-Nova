<?php

namespace App\Listeners;

use App\Element;
use Illuminate\Support\Facades\DB;

class PropagateElementToLanguageDatabases
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        throw new \Exception('If you need to use this command, then it needs to be rewitten to use the LanguageDatabaseService to handle language DB switching.');

        $model = $event->model;
        $uuid = $event->uuid;
        $currentLang = config()->get('database.connections.' . env('DB_CONNECTION', 'mysql') . '.database');

        collect(config()->get('database.connections.languages'))->reject(function ($db) use ($currentLang) {
            return $db === $currentLang;
        })->each(function ($db, $key) use ($model, $uuid) {
            config()->set('database.connections.' . env('DB_CONNECTION', 'mysql') . '.database', $db);
            DB::connection(env('DB_CONNECTION', 'mysql'))->reconnect();
            $modelClass = get_class($model);
            $newModel = (new $modelClass);
            // disable default behaviour that creates an element
            // when we create the model
            $newModel->flushEventListeners();
            $createModel = $newModel->create($model->toArray());
            Element::flushEventListeners();
            Element::create([
                'id' => $uuid,
                'element_type' => get_class($createModel),
                'element_id' => $createModel->id,
            ]);
        });
    }
}
