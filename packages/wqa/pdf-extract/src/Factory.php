<?php

namespace Wqa\PdfExtract;

class Factory
{
    /**
     * @param $area
     * @return mixed
     */
    public static function makeArea($area)
    {
        $namespace = __NAMESPACE__.'\\BodySystem\\';
        $className = $namespace.$area;
        if (class_exists($className)) {
            return new $className;
        }
    }
}