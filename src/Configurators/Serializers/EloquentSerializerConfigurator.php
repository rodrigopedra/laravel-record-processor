<?php

namespace RodrigoPedra\LaravelRecordProcessor\Configurators\Serializers;

use Illuminate\Database\Eloquent\Builder;
use RodrigoPedra\LaravelRecordProcessor\Serializers\EloquentSerializer;
use RodrigoPedra\RecordProcessor\Configurators\Serializers\SerializerConfigurator;

/**
 * @property  \RodrigoPedra\LaravelRecordProcessor\Serializers\EloquentSerializer $serializer
 */
class EloquentSerializerConfigurator extends SerializerConfigurator
{
    public function __construct(EloquentSerializer $serializer)
    {
        parent::__construct($serializer);
    }

    public function builder(): Builder
    {
        return $this->serializer->builder();
    }

    public function withShouldOutputModels(bool $shouldOutputModels): self
    {
        $this->serializer->withShouldOutputModels($shouldOutputModels);

        return $this;
    }
}
