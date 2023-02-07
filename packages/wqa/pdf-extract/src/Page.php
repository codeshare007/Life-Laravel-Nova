<?php

namespace Wqa\PdfExtract;

class Page
{
    protected $number;
    protected $layout;
    protected $progress;

    public function __construct()
    {
        $progress = $this->output->createProgressBar($this->pdf->getPages());
        $progress->start();
    }

    /**
     * @param mixed $number
     */
    public function setNumber($number): void
    {
        $this->number = $number;
    }

    protected function setLayout()
    {
        $this->layout->isThreeColumn();
    }
}
