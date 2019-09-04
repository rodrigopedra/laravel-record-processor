<?php

namespace RodrigoPedra\LaravelRecordProcessor\Stages;

use InvalidArgumentException;
use Illuminate\Support\Facades\Response;
use RodrigoPedra\RecordProcessor\Stages\DownloadFileOutput;
use RodrigoPedra\RecordProcessor\Contracts\ProcessorStageFlusher;

class DownloadFileResponse extends DownloadFileOutput implements ProcessorStageFlusher
{
    protected function downloadFile()
    {
        if ($this->inputFileInfo->isTempFile()) {
            throw new InvalidArgumentException('Cannot use a temporary file to make a download response');
        }

        if (! $this->inputFileInfo->isReadable() || $this->inputFileInfo->getRealPath() === false) {
            throw new InvalidArgumentException('Cannot read file for download');
        }

        $filename = rawurlencode($this->outputFileInfo->getBasename());

        return Response::download($this->inputFileInfo, $filename)
            ->deleteFileAfterSend($this->deleteAfterDownload);
    }
}
