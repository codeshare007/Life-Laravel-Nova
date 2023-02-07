<?php

namespace Wqa\PdfExtract;

use Gufy\PdfToHtml;

class Parser
{
    private $parser;

    /**
     * @param File $file
     * @return PdfToHtml\Pdf
     */
    public function make(File $file)
    {
        $this->setConfig();
        $this->parser = new PdfToHtml\Pdf($file->getPath());

        return $this->parser;
    }

    private function setConfig()
    {
        PdfToHtml\Config::set('pdftohtml.bin', '/usr/local/bin/pdftohtml');
        PdfToHtml\Config::set('pdfinfo.bin', '/usr/local/bin/pdfinfo');
    }

    /**
     * @param Pdf $pdf
     * @return mixed
     */
    public static function cleanup(Pdf $pdf)
    {
        $html = $pdf->getHtml();
        $rules = $pdf->config['rules'];
        if (isset($rules['mergeLines'])) {
            $html = Helper::mergeLines($html, $rules['mergeLines']);
        }

        return $html;
    }
}
