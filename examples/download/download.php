<?php

require __DIR__ . '/../../vendor/autoload.php';

use RodrigoPedra\LaravelRecordProcessor\Examples\ExamplesCommand;
use RodrigoPedra\LaravelRecordProcessor\ProcessorBuilder;
use RodrigoPedra\RecordProcessor\Configurators\Serializers\SerializerConfigurator;
use RodrigoPedra\RecordProcessor\Stages\DownloadFileOutput;

$storagePath = __DIR__ . '/../../storage/';

$command = new ExamplesCommand();

$processor = (new ProcessorBuilder())
    ->readFromEloquent($command->makeEloquentBuilder())
    ->serializeToExcelFile($storagePath . 'output.xlsx', function (SerializerConfigurator $configurator) {
        $configurator->withHeader(['name', 'email']);
    })
    ->downloadFileOutput('report.xlsx', DownloadFileOutput::DELETE_FILE_AFTER_DOWNLOAD)
    ->build();

$processor->process();

exit;
