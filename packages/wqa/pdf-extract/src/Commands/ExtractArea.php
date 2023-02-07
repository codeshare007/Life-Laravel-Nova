<?php

namespace Wqa\PdfExtract\Commands;

use Illuminate\Console\Command;
use Wqa\PdfExtract\Area;
use Wqa\PdfExtract\Factory;
use Wqa\PdfExtract\File;
use Wqa\PdfExtract\Parser;
use Wqa\PdfExtract\PdfApi;
use Wqa\PdfExtract\PdfExtract;

class ExtractArea extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pdf:extract-area {filename} {area} {page?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Extract information which is contained within a certain area from a PDF document';

    private $pdf;
    private $area;

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
    public function handle(File $file)
    {
        $filename = $this->argument('filename') ?? '5th Edition Essential Life (Body Systems).pdf';
        // TODO: Check if filename exists if not throw exception

        $file->setName($filename);

        $this->pdf = new PdfExtract;
        $this->pdf->execute($file);

        $results = $this->pdf->extract([
            'page' => $this->argument('page'),
            'area' => $this->argument('area')
        ]);

        return $results;
    }
}
