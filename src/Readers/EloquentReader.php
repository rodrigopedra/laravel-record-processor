<?php

namespace RodrigoPedra\LaravelRecordProcessor\Readers;

use Illuminate\Database\Eloquent\Builder;
use RodrigoPedra\LaravelRecordProcessor\Configurators\Readers\EloquentReaderConfigurator;
use RodrigoPedra\LaravelRecordProcessor\RecordParsers\EloquentRecordParser;
use RodrigoPedra\RecordProcessor\Concerns\CountsLines;
use RodrigoPedra\RecordProcessor\Concerns\Readers\HasInnerIterator;
use RodrigoPedra\RecordProcessor\Contracts\Reader;

class EloquentReader implements Reader
{
    use CountsLines;
    use HasInnerIterator;

    protected Builder $builder;
    protected EloquentReaderConfigurator $configurator;

    public function __construct(Builder $eloquentBuilder)
    {
        $this->builder = $eloquentBuilder;
        $this->configurator = new EloquentReaderConfigurator($this);
    }

    public function open()
    {
        $this->lineCount = 0;

        $iterator = $this->builder->cursor()->getIterator();
        $iterator = match (true) {
            $iterator instanceof \Iterator => $iterator,
            default => new \IteratorIterator($iterator),
        };

        $this->withInnerIterator($iterator);
    }

    public function close()
    {
        $this->withInnerIterator(null);
    }

    public function builder(): Builder
    {
        return $this->builder;
    }

    public function configurator(): EloquentReaderConfigurator
    {
        return $this->configurator;
    }

    public function defaultRecordParser(): EloquentRecordParser
    {
        return new EloquentRecordParser();
    }
}


