<?php

namespace RodrigoPedra\LaravelRecordProcessor\Stages;

use Illuminate\Contracts\Routing\ResponseFactory;
use RodrigoPedra\RecordProcessor\Contracts\ProcessorStageFlusher;
use RodrigoPedra\RecordProcessor\Stages\DownloadFileOutput;
use RodrigoPedra\RecordProcessor\Stages\TransferObjects\FlushPayload;

class FileDowloadResponse extends DownloadFileOutput implements ProcessorStageFlusher
{
    public function flush( FlushPayload $payload )
    {
        $fileInfo = $this->getOutputFileInfo( $payload );
        $filename = rawurlencode( $this->downloadFileInfo->getBasename() );

        /** @var ResponseFactory $factory */
        $factory = app( ResponseFactory::class );

        $response = $factory->download( $fileInfo, $filename );

        $payload->setOutput( $response );

        return $payload;
    }
}
