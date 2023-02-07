<?php

namespace Wqa\PdfExtract;

class File
{
    protected $name;

    /**
     * @return mixed
     */
    public function getPath()
    {
        return storage_path($this->name);
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getNameWithoutExtension()
    {
        return pathinfo($this->name, PATHINFO_FILENAME);
    }
}
