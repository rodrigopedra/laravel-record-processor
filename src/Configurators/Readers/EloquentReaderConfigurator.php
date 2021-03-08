<?php

namespace RodrigoPedra\LaravelRecordProcessor\Configurators\Readers;

use Illuminate\Database\Eloquent\Builder;
use RodrigoPedra\LaravelRecordProcessor\Readers\EloquentReader;
use RodrigoPedra\RecordProcessor\Configurators\Readers\ReaderConfigurator;

/**
 * @property  \RodrigoPedra\LaravelRecordProcessor\Readers\EloquentReader $reader
 */
class EloquentReaderConfigurator extends ReaderConfigurator
{
    public function __construct(EloquentReader $reader)
    {
        parent::__construct($reader);
    }

    public function builder(): Builder
    {
        return $this->reader->builder();
    }
}
