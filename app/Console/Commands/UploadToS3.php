<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Request;

class UploadToS3 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'upload:s3 {model}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Upload images to S3';

    protected $images = [];

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
        $this->getImagesFromModel();
    }

    protected function getImagesFromModel(Request $request)
    {
        $useable = $this->argument('model');
        $model = $useable::all();
        dd($model);

        $this->images = [];

        if ($request->has('uploadToCloud')) {
            foreach ($bodySystems as $bodySystem) {
                if (!empty($bodySystem->image_url)) {
                    continue;
                }

                $imageUrl = config('app.url').'/body-systems/'.str_replace([' ', '&', '--', '\''], ['-'. '', '-', ''], title_case(str_replace('/', '-', $bodySystem->name))).'.jpg';
                dd($imageUrl, title_case($bodySystem->name));

                $file = @file_get_contents($imageUrl);
                if (!$file) {
                    continue;
                }
                $filePath = 'recipes/'.md5_file($imageUrl).'.jpeg';
                if (Storage::put($filePath, $file)) {
                    $bodySystem->image_url = Storage::url($filePath);
                    if ($bodySystem->save()) {
                        $images[] = $bodySystem->image_url;
                    }
                }
            }
            dd($images);
        }
    }
}
