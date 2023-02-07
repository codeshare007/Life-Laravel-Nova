<?php

namespace Wqa\PdfExtract;

use League\Csv\CharsetConverter;
use League\Csv\Reader;
use League\Csv\Statement;

class PdfExtract
{
    protected $pdf;
    protected $api;
    protected $layout;
    protected $area;
    public $page;

    /**
     * PdfExtract constructor.
     */
    public function __construct()
    {
        $this->setApi();
        $this->getApi()->initialise()->setTask('extract');
    }

    /**
     * @param File $file
     * @return mixed
     */
    public function execute(File $file)
    {
        return $this->getApi()->addFile($file)->execute();
    }

    public function extract($params)
    {
        $this->setArea($params['area']);
        $this->setPage($params['page']);

        $csv = Reader::createFromPath($this->getApi()->getDownloadFilePath(), 'r');
        $csv->setHeaderOffset(0);

        if ($csv->getInputBOM() === Reader::BOM_UTF16_LE) {
            CharsetConverter::addTo($csv, 'UTF-16LE', 'UTF-8');
        } elseif ($csv->getInputBOM() === Reader::BOM_UTF16_BE) {
            CharsetConverter::addTo($csv, 'UTF-16BE', 'UTF-8');
        }

        $this->area = Factory::makeArea($this->getArea());

        $stmt = (new Statement())->where(function(array $record) use ($params) {
            return isset($params['page']) ? $record['PageNo'] == $params['page'] : true;
        })->limit(-1);

        $search = new SearchIndex;
        return $this->area->search(collect($stmt->process($csv)), $search, $params);
    }

    /**
     * @param mixed $area
     */
    public function setArea($area): void
    {
        $this->area = $area;
    }

    /**
     * @param mixed $page
     */
    public function setPage($page): void
    {
        $this->page = $page;
    }

    /**
     * @return mixed
     */
    public function getArea()
    {
        return $this->area;
    }

    public function getPages()
    {
        return $this->parser->getPages();
    }

    /**
     * @param $page
     * @return mixed
     */
    public function html($page)
    {
        $this->setPdf($page);
        return $this->pdf->getHtml();
    }

    /**
     * @return mixed
     */
    public function getPdf()
    {
        return $this->pdf;
    }

    /**
     * @param mixed $pdf
     */
    public function setPdf($page): void
    {
        $this->pdf = new Pdf($this->parser->html($page));
    }

    /**
     * @return PdfApi $api
     */
    public function getApi()
    {
        return $this->api;
    }

    /**
     * @param PdfApi $api
     */
    public function setApi(): void
    {
        $this->api = new PdfApi;
    }

    private function setTask()
    {
    }
}
