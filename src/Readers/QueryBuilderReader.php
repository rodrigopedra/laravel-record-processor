<?php

namespace RodrigoPedra\LaravelRecordProcessor\Readers;

use Illuminate\Database\Query\Builder;
use RodrigoPedra\LaravelRecordProcessor\Configurators\Readers\QueryBuilderReaderConfigurator;
use RodrigoPedra\RecordProcessor\Concerns\CountsLines;
use RodrigoPedra\RecordProcessor\Concerns\Readers\HasInnerIterator;
use RodrigoPedra\RecordProcessor\Contracts\Reader;
use RodrigoPedra\RecordProcessor\Contracts\RecordParser;
use RodrigoPedra\RecordProcessor\RecordParsers\ArrayRecordParser;

final class QueryBuilderReader implements Reader
{
    use CountsLines;
    use HasInnerIterator;

    private readonly QueryBuilderReaderConfigurator $configurator;

    public function __construct(
        private readonly Builder $queryBuilder,
    ) {
        $this->configurator = new QueryBuilderReaderConfigurator($this);
    }

    public function open(): void
    {
        $this->lineCount = 0;

        $iterator = $this->queryBuilder->cursor()->getIterator();
        $iterator = match (true) {
            $iterator instanceof \Iterator => $iterator,
            default => new \IteratorIterator($iterator),
        };

        $this->withInnerIterator($iterator);
    }

    public function close(): void
    {
        $this->withInnerIterator(null);
    }

    public function queryBuilder(): Builder
    {
        return $this->queryBuilder;
    }

    public function configurator(): QueryBuilderReaderConfigurator
    {
        return $this->configurator;
    }

    public function defaultRecordParser(): RecordParser
    {
        return new ArrayRecordParser();
    }
}
