<?php

namespace App\Console\Commands\ApiResource;

use Illuminate\Console\Command;

class ApiResourceGenerator extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'api-resource:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates the API controller and resource file for a given version';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $version = $this->ask('Specify the version of the api you\'re generating using underscores (1_0). Folders will be generated using this in the format /v1_0');

        $modelName = $this->ask('Model name the api resource is for');
        $controllerName = $modelName . 'Controller';
        $resourceName = $modelName . 'Resource';

        $modelDir = $this->ask(
            'Model directories. Defaults to app/',
            'app/'
        );

        $controllerDir = $this->ask(
            'Folder the generated controller will reside. Defaults to app/Http/Controllers/Api/v' . $version . '/' . $modelName .'/',
            'app/Http/Controllers/Api/v' . $version . '/' . $modelName
        );

        $resourceDir = $this->ask(
            'Folder the generated JsonResource will reside. Defaults to app/Http/Resources/v' . $version,
            'app/Http/Resources/v' . $version
        );

        // create controller folder if not exists
        if (!file_exists($controllerDir)) {
            mkdir($controllerDir);
        }

        // create resource controller if not exists
        if (!file_exists($resourceDir)) {
            mkdir($resourceDir);
        }

        // generate controller with given stub
        $controllerTemplate = str_replace(
            [
                '{{modelName}}',
                '{{lcModelName}}',
                '{{controllerFolder}}',
                '{{modelDir}}'
            ],
            [
                $modelName,
                lcfirst($modelName),
                ucfirst(str_replace('/', '\\', $controllerDir)),
                ucfirst(str_replace('/', '\\', $modelDir)),
            ],
            $this->getControllerStub()
        );

        file_put_contents($controllerDir . '/' . $controllerName .'.php', $controllerTemplate);

        // generate controller with given stub
        $resourceContents = str_replace(
            [
                '{{resourceFolder}}',
                '{{resourceName}}',
            ],
            [
                ucfirst(str_replace('/', '\\', $resourceDir)),
                $resourceName,
            ],
            $this->getResourceStub()
        );

        file_put_contents($resourceDir . '/' . $resourceName .'.php', $resourceContents);
    }

    /**
     * @return false|string
     */
    protected function getControllerStub()
    {
        return file_get_contents(__DIR__ . '/Controller.stub');
    }

    /**
     * @return false|string
     */
    protected function getResourceStub()
    {
        return file_get_contents(__DIR__ . '/Resource.stub');
    }
}
