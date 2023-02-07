<?php

namespace App\Console\Commands;

use App\Tag;
use App\BodySystem;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class AddPropertyTagsToBodySystemsUsingPDFExtract extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wqa:add-property-tags-to-body-systems-using-pdf-extract';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Adds the property tags to body systems from the pdf extraction.';

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
        $importableProperties = DB::table('pdf_extract_related_properties')->orderBy('id')->get();
        $tags = Tag::all()->map(function($tag) {
            $tag['normalizedName'] = $this->normalize($tag->name);
            return $tag;
        });

        $importableProperties->each(function($importableProperty) use ($tags) {

            $tag = $tags->where('normalizedName', '=', $this->normalize($importableProperty->name))->first();
            $bodySystem = $this->getBodySystemByPageNumber($importableProperty->page);

            if ($tag) {
                $bodySystem->properties()->syncWithoutDetaching($tag->id);
            } else {
                $this->info("Skipping over $importableProperty->name, it's probably not a tag...");
            }
        });
    }

    private function getBodySystemByPageNumber(string $pageNumber): BodySystem
    {
        $map = [
            '2' => 'Addiction',
            '5' => 'Allergies',
            '7' => 'Athletes',
            '9' => 'Autoimmune',
            '13' => 'Blood Sugar',
            '15' => 'Brain',
            '18' => 'Candida',
            '22' => 'Cardiovascular',
            '25' => 'Cellular Health',
            '28' => 'Children',
            '42' => 'Detoxification',
            '47' => 'Digestive',
            '52' => 'Eating Disorders',
            '56' => 'Endocrine',
            '60' => 'Energy & Vitality',
            '63' => 'First Aid',
            '67' => 'Focus',
            '71' => 'Immune & Lymphatic',
            '76' => 'Integumentary',
            '82' => 'Intimacy',
            '85' => 'Limbic',
            '88' => 'Men\'s Health',
            '91' => 'Mood & Behavior',
            '95' => 'Muscular',
            '99' => 'Nervous System',
            '103' => 'Oral Health',
            '106' => 'Pain & Inflammation',
            '110' => 'Parasites',
            '113' => 'Pregnancy',
            '119' => 'Respiratory',
            '125' => 'Skeletal',
            '129' => 'Sleep',
            '133' => 'Stress',
            '136' => 'Urinary',
            '139' => 'Weight',
            '144' => 'Women\'s Health',
        ];

        $bodySystem = BodySystem::where('name', $map[$pageNumber])->first();

        if (! $bodySystem) {
            $this->error('No body system found with the name "' . $map[$pageNumber] . '".');
            die();
        }

        return $bodySystem;
    }

    private function normalize($string)
    {
        return str_slug($string, '');
    }
}
