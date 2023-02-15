<?php

namespace RodrigoPedra\LaravelRecordProcessor\Configurators\Serializers;

use Illuminate\Database\Query\Builder;
use RodrigoPedra\LaravelRecordProcessor\Serializers\QueryBuilderSerializer;
use RodrigoPedra\RecordProcessor\Configurators\Serializers\SerializerConfigurator;

/**
 * @property  \RodrigoPedra\LaravelRecordProcessor\Serializers\QueryBuilderSerializer $serializer
 */
class QueryBuilderSerializerConfigurator extends SerializerConfigurator
{
    public function __construct(QueryBuilderSerializer $serializer)
    {
        parent::__construct($serializer);
    }

    public function queryBuilder(): Builder
    {
        return $this->serializer->queryBuilder();
    }
}
