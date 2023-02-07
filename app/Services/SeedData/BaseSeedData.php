<?php

namespace App\Services\SeedData;

use App\Enums\UserLanguage;
use App\Services\LanguageDatabaseService;
use Illuminate\Contracts\Support\Jsonable;

abstract class BaseSeedData implements Jsonable
{
    protected $changedLanguage = false;
    protected $seedData = [];
    protected $languageDatabaseService;

    public function __construct()
    {
        $this->languageDatabaseService = resolve(LanguageDatabaseService::class);
    }

    public function __destruct()
    {
        if ($this->changedLanguage) {
            $this->languageDatabaseService->reset();
        }
    }

    public function setLanguage(UserLanguage $language): self
    {
        $this->languageDatabaseService->setLanguage($language);
        $this->changedLanguage = true;

        return $this;
    }

    /**
     * Convert the data to JSON.
     *
     * @param  int  $options
     * @return string
     *
     * @throws \Illuminate\Database\Eloquent\JsonEncodingException
     */
    public function toJson($options = 0)
    {
        $json = json_encode($this->seedData, $options);

        if (JSON_ERROR_NONE !== json_last_error()) {
            throw JsonEncodingException::forModel($this, json_last_error_msg());
        }

        return $json;
    }

    /**
     * Convert the data to array.
     *
     * @return string
     *
     * @throws \Illuminate\Database\Eloquent\JsonEncodingException
     */
    public function toArray()
    {
        return $this->seedData;
    }

    abstract public function build(): self;
}
