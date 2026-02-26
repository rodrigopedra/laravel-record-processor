<?php

namespace RodrigoPedra\LaravelRecordProcessor\Configurators\Readers;

use Illuminate\Contracts\Database\Query\Builder;
use RodrigoPedra\LaravelRecordProcessor\Readers\QueryBuilderReader;
use RodrigoPedra\RecordProcessor\Configurators\Readers\ReaderConfigurator;

/**
 * @property  \RodrigoPedra\LaravelRecordProcessor\Readers\QueryBuilderReader $reader
 */
class QueryBuilderReaderConfigurator extends ReaderConfigurator
{
    public function __construct(QueryBuilderReader $reader)
    {
        parent::__construct($reader);
    }

    public function queryBuilder(): Builder
    {
        return $this->reader->queryBuilder();
    }
}
