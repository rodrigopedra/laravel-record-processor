<?php

namespace RodrigoPedra\LaravelRecordProcessor\Serializers;

use Illuminate\Database\Query\Builder;
use RodrigoPedra\LaravelRecordProcessor\Configurators\Serializers\QueryBuilderSerializerConfigurator;
use RodrigoPedra\RecordProcessor\Concerns\CountsLines;
use RodrigoPedra\RecordProcessor\Concerns\NoOutput;
use RodrigoPedra\RecordProcessor\Configurators\Serializers\SerializerConfigurator;
use RodrigoPedra\RecordProcessor\Contracts\Serializer;
use RodrigoPedra\RecordProcessor\RecordSerializers\ArrayRecordSerializer;

class QueryBuilderSerializer implements Serializer
{
    use CountsLines;
    use NoOutput;

    protected Builder $queryBuilder;
    protected SerializerConfigurator $configurator;

    public function __construct(Builder $queryBuilder)
    {
        $this->queryBuilder = $queryBuilder;
        $this->configurator = new QueryBuilderSerializerConfigurator($this);
    }

    public function queryBuilder(): Builder
    {
        return $this->queryBuilder;
    }

    public function open()
    {
        $this->lineCount = 0;
    }

    public function close()
    {
        //
    }

    public function append($content)
    {
        if (! $this->isAssociative($content)) {
            throw new \RuntimeException('Content for QueryBuilderWriter should be an associative array');
        }

        if (! $this->queryBuilder->insert($content)) {
            throw new \RuntimeException('Could not serialize record');
        }

        $this->incrementLineCount();
    }

    public function configurator(): QueryBuilderSerializerConfigurator
    {
        return $this->configurator;
    }

    public function defaultRecordSerializer(): ArrayRecordSerializer
    {
        return new ArrayRecordSerializer();
    }

    protected function isAssociative(array $array): bool
    {
        foreach ($array as $key => $value) {
            return \is_string($key);
        }

        return false;
    }
}
