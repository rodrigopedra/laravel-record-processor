<?php

namespace RodrigoPedra\LaravelRecordProcessor;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use RodrigoPedra\LaravelRecordProcessor\Readers\EloquentReader;
use RodrigoPedra\LaravelRecordProcessor\Readers\QueryBuilderReader;
use RodrigoPedra\LaravelRecordProcessor\Records\Formatter\EloquentRecordFormatter;
use RodrigoPedra\LaravelRecordProcessor\Records\Parsers\EloquentRecordParser;
use RodrigoPedra\LaravelRecordProcessor\Stages\DownloadFileResponse;
use RodrigoPedra\LaravelRecordProcessor\Writers\EloquentWriter;
use RodrigoPedra\LaravelRecordProcessor\Writers\QueryBuilderWriter;
use RodrigoPedra\RecordProcessor\ProcessorBuilder as BaseBaseProcessorBuilder;
use RodrigoPedra\RecordProcessor\Records\Formatter\ArrayRecordFormatter;
use RodrigoPedra\RecordProcessor\Records\Parsers\ArrayRecordParser;

class ProcessorBuilder extends BaseBaseProcessorBuilder
{
    public function readFromEloquent( Builder $eloquentBuilder, callable $configurator = null )
    {
        $this->reader = new EloquentReader( $eloquentBuilder );

        if (is_null( $this->recordParser )) {
            $this->usingParser( new EloquentRecordParser );
        }

        $this->configureReader( $this->reader, $configurator );

        return $this;
    }

    public function readFromQueryBuilder( QueryBuilder $queryBuilder, callable $configurator = null )
    {
        $this->reader = new QueryBuilderReader( $queryBuilder );

        if (is_null( $this->recordParser )) {
            $this->usingParser( new ArrayRecordParser );
        }

        $this->configureReader( $this->reader, $configurator );

        return $this;
    }

    public function writeToEloquent( Builder $eloquentBuilder, callable $configurator = null )
    {
        $writer = new EloquentWriter( $eloquentBuilder );

        if (is_null( $this->recordFormatter )) {
            $model = $eloquentBuilder->getModel();

            $this->usingFormatter( new EloquentRecordFormatter( $model ) );
        }

        $this->addCompiler( $writer, $this->configureWriter( $writer, $configurator ) );

        return $this;
    }

    public function writeToQueryBuilder( QueryBuilder $queryBuilder, callable $configurator = null )
    {
        $writer = new QueryBuilderWriter( $queryBuilder );

        if (is_null( $this->recordFormatter )) {
            $this->usingFormatter( new ArrayRecordFormatter );
        }

        $this->addCompiler( $writer, $this->configureWriter( $writer, $configurator ) );

        return $this;
    }

    public function downloadFileResponse( $outputFilename = '', $deleteFileAfterDownload = false )
    {
        $this->addStage( new DownloadFileResponse( $outputFilename, $deleteFileAfterDownload ) );

        return $this;
    }
}
