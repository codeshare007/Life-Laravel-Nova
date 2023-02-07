<?php

namespace App\Services;

use Exception;
use App\Enums\UserLanguage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use Illuminate\Database\ConnectionInterface;

class LanguageDatabaseService
{
    protected $originalConnectionName;

    public function __construct()
    {
        $this->originalConnectionName = $this->currentConnection()->getName();
    }

    public function setLanguage(UserLanguage $language): void
    {
        $newConnectionName = config()->get('database.languages.' . $language->value);
        $this->changeConnection($newConnectionName);
        App::setLocale($language->value);
    }

    public function reset(): void
    {
        $this->changeConnection($this->originalConnectionName);
    }

    public function eachDatabase(callable $callback): void
    {
        $preLoopLanguage = $this->currentLanguage();

        foreach (UserLanguage::getInstances() as $language) {
            $this->setLanguage($language);

            $returnValue = $callback($language);

            if ($returnValue) {
                break;
            }
        };

        $this->setLanguage($preLoopLanguage);
    }

    protected function changeConnection(string $connectionName): void
    {
        abort_unless($connectionName, 500, 'Cannot connect to database. Connection name not set.');

        $currentConnection = DB::connection()->getName();

        if ($currentConnection !== $connectionName) {
            try {
                DB::setDefaultConnection($connectionName);
            } catch (Exception $e) {
                throw new Exception('Could not connect database connection named: ' . $connectionName);
            }
        }
    }

    public function languageFromConnectionName(string $connectionName): UserLanguage
    {
        $connections = config()->get('database.languages');
        $languageCode = array_search($connectionName, $connections, true);

        return UserLanguage::getInstance($languageCode);
    }

    public function currentConnection(): ConnectionInterface
    {
        return DB::connection();
    }

    public function currentLanguage(): UserLanguage
    {
        return $this->languageFromConnectionName($this->currentConnection()->getName());
    }
}
