<?php

namespace Wqa\PdfExtract\Tests;

use Wqa\PdfExtract\Facades\PdfExtract;
use Wqa\PdfExtract\ServiceProvider;
use Orchestra\Testbench\TestCase;

class PdfExtractTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [ServiceProvider::class];
    }

    protected function getPackageAliases($app)
    {
        return [
            'pdf-extract' => PdfExtract::class,
        ];
    }

    public function testExample()
    {
        $this->assertEquals(1, 1);
    }
}
