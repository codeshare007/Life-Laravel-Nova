<?php

namespace Wqa\PdfExtract;

class Pdf
{

    protected $layout;
    public $config;
    protected $html;

    public function __construct($html)
    {
        $this->loadConfig();

        $this->setHtml($html);

        if ($this->config['cleanup']) {
            $this->setHtml(Parser::cleanup($this), false);
        }

        $this->setLayout();
    }

    protected function setLayout(): void
    {
        $this->layout = new Layout($this->html, $this->config);
    }

    /**
     * @return mixed
     */
    public function getLayout()
    {
        return $this->layout;
    }

    private function loadConfig()
    {
        // Todo: Load yaml file for each PDF settings

        $config = [
            'area' => [
               'BodySystem' => [
                   'Conditions',
                   'RelatedProperties'
               ]
            ],
            'layout' => [
                'twoColumn' => [
                    'right' => ['leftPosition' => 440],
                ],
                'threeColumn' => [
                    'center' => ['leftPosition' => 320],
                    'right' => ['leftPosition' => 590],
                ],
                'footer' => ['topPosition' => 50],
                'margin' => ['x' => 50]
            ],
            'columnCondition' => [
                'minElementsNb' => 20,
            ],
            'rules' => [
                'mergeLines' => [
                    'event' => 'before',
                    'merge' => 'next',
                    'position' => 'top',
                    'diff' => 4,
                ],
            ],
            'exclude' => ['footer', 'image', 'pageNb', 'sectionTab'],
            'cleanup' => true,
        ];

        $this->setConfig($config);
    }

    /**
     * @return mixed
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @param mixed $config
     */
    public function setConfig($config): void
    {
        $this->config = $config;
    }

    /**
     * @return mixed
     */
    public function getHtml()
    {
        return $this->html;
    }

    /**
     * @param $html
     * @param bool $toArray
     */
    public function setHtml($html, $toArray = true): void
    {
        $this->html = $toArray ? explode(PHP_EOL, $html) : $html;
    }
}
