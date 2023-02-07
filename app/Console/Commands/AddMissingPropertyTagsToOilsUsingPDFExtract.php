<?php

namespace App\Console\Commands;

use App\Oil;
use App\Tag;
use App\Enums\TagType;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class AddMissingPropertyTagsToOilsUsingPDFExtract extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wqa:add-missing-property-tags-to-oils-using-pdf-extract';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Adds the missing property tags to oils from the pdf extraction.';

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
        // Create the "Relaxing" tag if it doesn't exist already... it's the only on in the export which is missing.
        Tag::firstOrCreate([
            'name' => 'Relaxing',
            'type' => TagType::Property,
        ]);

        $importableProperties = DB::table('pdf_extract_related_properties')->orderBy('id')->get();
        $tags = Tag::all()->map(function($tag) {
            $tag['normalizedName'] = $this->normalize($tag->name);
            return $tag;
        });

        $importableProperties->each(function($importableProperty) use ($tags) {

            $tag = $tags->where('normalizedName', '=', $this->normalize($importableProperty->name))->first();

            if (! $tags->contains('normalizedName', $this->normalize($importableProperty->name))) {
                $this->info("Skipping over $importableProperty->name, it's probably not a tag...");
            } else {
                $oils = $this->getOilsCollectionFromListOfNames($importableProperty->solutions);
                $oilIds = $oils->pluck('id');
                $tag->oils()->syncWithoutDetaching($oilIds);
            }
        });
    }

    private function normalize($string)
    {
        return str_slug($string, '');
    }

    private function getOilsCollectionFromListOfNames(string $list): Collection
    {
        $array = explode(', ', $list);

        return Oil::whereIn('name', $array)->get();
    }
}
