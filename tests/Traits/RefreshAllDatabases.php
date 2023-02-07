<?php

namespace Tests\Traits;

use Illuminate\Contracts\Console\Kernel;
use App\Services\LanguageDatabaseService;
use Illuminate\Foundation\Testing\RefreshDatabase;

trait RefreshAllDatabases
{
    use RefreshDatabase;
    
    /**
     * Refresh the in-memory database.
     *
     * @return void
     */
    protected function refreshInMemoryDatabase()
    {
        (new LanguageDatabaseService())->eachDatabase(function() {
            $this->artisan('migrate');
        });

        $this->app[Kernel::class]->setArtisan(null);
    }
}
