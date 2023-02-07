<?php

namespace Wqa\PdfExtract;

class Layout
{
    protected $columns;
    protected $html;

    public function __construct($html, $config)
    {
        $this->detectColumns($html);
        $this->setHtml($html, $config);
    }

    /**
     * @return mixed
     */
    public function getColumns()
    {
        return $this->columns;
    }

    /**
     * @param mixed $columns
     */
    public function setColumns($columns): void
    {
        $this->columns = $columns;
    }

    /**
     * @param $html
     */
    protected function detectColumns($html)
    {
        // Todo: get values from PDF config
        $threeColumnRight = 590;
        $minElementsNb = 20;

        $threeColumns = false;
        $elementsNb = 0;

        //dd($html);
        foreach ($html as $key => $line) {
            $value = Helper::getPositionValue('left', $line);
            if ($value > $threeColumnRight) {
                $elementsNb++;
            }

            if ($elementsNb == $minElementsNb) {
                $threeColumns = true;
                break;
            }
        }

        $this->setColumns($threeColumns ? 3 : 2);
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
     * @param $config
     */
    public function setHtml($html, $config): void
    {
        $columns = [];
        foreach ($html as $key => $line) {
            $value = Helper::getPositionValue('left', $line);
            if (!$value) {
                continue;
            }

            if ($value < 30 || $value > 880) {
                continue;
            }

            if ($this->columns == 3) {
                if ($value > $config['layout']['threeColumn']['right']['leftPosition']) {
                    $columns['right'][] = $line;
                } elseif ($value > $config['layout']['threeColumn']['center']['leftPosition'] && $value < $config['layout']['threeColumn']['right']['leftPosition']) {
                    $columns['center'][] = $line;
                } else {
                    $columns['left'][] = $line;
                }
            } else {
                if ($value > $config['layout']['twoColumn']['right']['leftPosition']) {
                    $columns['right'][] = $line;
                } else {
                    $columns['left'][] = $line;
                }
            }
        }

        $positions = [];
        foreach ($columns as $column => $colLines) {
            if (is_array($colLines)) {
                $positions[$column] = [];
                foreach ($colLines as $key => $line) {
                    $value = Helper::getPositionValue('top', $line);
                    if (!$value) {
                        continue;
                    }

                    $positions[$column][$key] = $value;
                }
                asort($positions[$column]);
            }
        }

        $colsToSort = [];
        foreach ($positions as $column => $positionKeys) {
            foreach ($positionKeys as $k => $positionKey) {
                $colsToSort[$column][] = $columns[$column][$k];
            }
        }

        $this->html = $colsToSort;
    }
}
