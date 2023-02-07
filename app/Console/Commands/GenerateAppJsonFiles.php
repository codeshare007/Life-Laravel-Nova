<?php

namespace App\Console\Commands;

use App\Enums\UserLanguage;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use App\Services\LanguageDatabaseService;
use App\Services\SeedData\ElementsSeedData;
use App\Services\SeedData\CommunityAuthorsSeedData;

class GenerateAppJsonFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wqa:generate-app-json-files';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates the app json files';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(LanguageDatabaseService $languageDatabaseService)
    {
        $this->languageDatabaseService = $languageDatabaseService;

        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->languageDatabaseService->eachDatabase(function (UserLanguage $language) {
            $elementsSeedData = (new ElementsSeedData)->build();
            $communityAuthorsSeedData = (new CommunityAuthorsSeedData)->build();
    
            $appJson = json_encode([
                'elements' => $elementsSeedData->toArray(),
                'communityAuthors' => $communityAuthorsSeedData->toArray(),
            ]);
    
            Storage::disk('local')->put('app-json/' . $language->value . '.json', $appJson);
        });
    }
}
