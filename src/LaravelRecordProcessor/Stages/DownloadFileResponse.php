<?php

namespace RodrigoPedra\LaravelRecordProcessor\Stages;

use InvalidArgumentException;
use RodrigoPedra\RecordProcessor\Contracts\ProcessorStageFlusher;
use RodrigoPedra\RecordProcessor\Stages\DownloadFileOutput;

class DownloadFileResponse extends DownloadFileOutput implements ProcessorStageFlusher
{
    protected function downloadFile()
    {
        if ($this->inputFileInfo->isTempFile()) {
            throw new InvalidArgumentException( 'Cannot use a temporary file to make a download response' );
        }

        $filename = rawurlencode( $this->outputFileInfo->getBasename() );

        return response()
            ->download( $this->inputFile, $filename )
            ->deleteFileAfterSend( $this->deleteAfterDownload );
    }
}
