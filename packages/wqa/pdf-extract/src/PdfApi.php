<?php

namespace Wqa\PdfExtract;

use Illuminate\Support\Facades\Storage;
use Ilovepdf\Ilovepdf;

class PdfApi
{
    private $api;
    private $task;
    private $downloadFilePath;
    private $apiKeys;

    public function initialise()
    {
        $this->setApiKeys();
        $this->api = new Ilovepdf($this->apiKeys['public'], $this->apiKeys['secret']);

        return $this;
    }

    private function setApiKeys()
    {
        $this->apiKeys = [
            'public' => 'project_public_2b64656184257950f45b253bf6d41c70_P_-_77406bd3a37da479b31b09baca48ce5cb',
            'secret' => 'secret_key_5f33fe11807ba9fce0b6ece6fe29b3fd_sI4r5909d8ee43da8436f6fc3e6cee63295c9'
        ];
    }

    public function addFile(File $file)
    {
        $this->setDownloadFilePath($file);
        $this->task->addFile($file->getPath());

        return $this;
    }

    /**
     * @return mixed
     */
    public function execute()
    {
        if (!file_exists($this->downloadFilePath)) {
            $this->task->execute()->download(storage_path('pdf-api'));
        }

        return $this->task;
    }

    public function setTask($task)
    {
        $this->task = $this->api->newTask($task);
        if ($task == 'extract') {
            $this->task->setDetailed(true);
        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDownloadFilePath()
    {
        return $this->downloadFilePath;
    }

    /**
     * @param mixed $file
     */
    public function setDownloadFilePath($file): void
    {
        $this->downloadFilePath = storage_path('pdf-api/'.$file->getNameWithoutExtension().'.csv');
    }
}
