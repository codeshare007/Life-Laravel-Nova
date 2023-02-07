<?php

namespace App\Console\Commands;

use App\Usage;
use App\Enums\UserLanguage;
use Illuminate\Console\Command;
use Rap2hpoutre\FastExcel\FastExcel;
use App\Services\LanguageDatabaseService;

class ImportUsageNameTranslationsForEsAndPt extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wqa:import-usage-name-translations-for-es-and-pt';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Replace the existing name with the translated name for each usage. For ES and PT only.';

    protected $languageDatabaseService;

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
        $languages = [
            UserLanguage::Spanish(),
            UserLanguage::Portugese(),
        ];

        $importData = (new FastExcel)->import(app_path('translations/usages-names.csv'));

        foreach ($languages as $language) {
            $this->languageDatabaseService->setLanguage($language);

            foreach ($importData as $importDataRow) {
                $usages = Usage::where('name', $importDataRow['en'])->get();

                if ($usages->count() > 0) {
                    $usages->each(function(Usage $usage) use($importDataRow, $language) {
                        $usage->flushEventListeners();
                        $usage->timestamps = false;

                        $usage->update([
                            'name' => $importDataRow[$language->value],
                        ]);
                    });
                }
            }
        }
    }
}
