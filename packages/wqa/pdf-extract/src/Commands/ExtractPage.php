<?php

namespace Wqa\PdfExtract\Commands;

use Illuminate\Console\Command;

class ExtractPage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pdf:extract-page {page?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Extract information from a specific page in a PDF document';

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

    }
}
