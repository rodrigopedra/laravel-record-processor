<?php

namespace RodrigoPedra\LaravelRecordProcessor;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use RodrigoPedra\LaravelRecordProcessor\Readers\EloquentReader;
use RodrigoPedra\LaravelRecordProcessor\Readers\QueryBuilderReader;
use RodrigoPedra\LaravelRecordProcessor\Serializers\EloquentSerializer;
use RodrigoPedra\LaravelRecordProcessor\Serializers\QueryBuilderSerializer;
use RodrigoPedra\RecordProcessor\ProcessorBuilder as BaseBaseProcessorBuilder;

class ProcessorBuilder extends BaseBaseProcessorBuilder
{
    public function readFromEloquent(Builder $eloquentBuilder, callable $configurator = null): self
    {
        $this->reader = new EloquentReader($eloquentBuilder);

        $this->configureReader($this->reader, $configurator);

        return $this;
    }

    public function readFromQueryBuilder(QueryBuilder $queryBuilder, callable $configurator = null): self
    {
        $this->reader = new QueryBuilderReader($queryBuilder);

        $this->configureReader($this->reader, $configurator);

        return $this;
    }

    public function writeToEloquent(Builder $eloquentBuilder, callable $configurator = null): self
    {
        $serializer = new EloquentSerializer($eloquentBuilder);

        $this->configureSerializer($serializer, $configurator);
        $this->addSerializer($serializer);

        return $this;
    }

    public function writeToQueryBuilder(QueryBuilder $queryBuilder, callable $configurator = null): self
    {
        $serializer = new QueryBuilderSerializer($queryBuilder);

        $this->configureSerializer($serializer, $configurator);
        $this->addSerializer($serializer);

        return $this;
    }
}
