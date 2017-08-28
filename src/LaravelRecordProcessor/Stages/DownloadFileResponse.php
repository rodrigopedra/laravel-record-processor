<?php

namespace RodrigoPedra\LaravelRecordProcessor\Stages;

use Illuminate\Contracts\Routing\ResponseFactory;
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

        /** @var ResponseFactory $factory */
        $factory = app( ResponseFactory::class );

        $response = $factory->download( $this->inputFile, $filename );
        $response->deleteFileAfterSend( $this->deleteAfterDownload );

        return $response;
    }
}
