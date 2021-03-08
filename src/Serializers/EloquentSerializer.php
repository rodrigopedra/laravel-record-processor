<?php

namespace RodrigoPedra\LaravelRecordProcessor\Serializers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use RodrigoPedra\LaravelRecordProcessor\Configurators\Serializers\EloquentSerializerConfigurator;
use RodrigoPedra\LaravelRecordProcessor\RecordSerializers\EloquentRecordSerializer;
use RodrigoPedra\RecordProcessor\Concerns\CountsLines;
use RodrigoPedra\RecordProcessor\Contracts\Serializer;

class EloquentSerializer implements Serializer
{
    use CountsLines;

    protected Builder $builder;
    protected ?Collection $results = null;
    protected bool $shouldOutputModels = false;
    protected EloquentSerializerConfigurator $configurator;

    public function __construct(Builder $eloquentBuilder)
    {
        $this->builder = $eloquentBuilder;

        $this->withShouldOutputModels(false);
        $this->configurator = new EloquentSerializerConfigurator($this);
    }

    public function builder(): Builder
    {
        return $this->builder;
    }

    public function withShouldOutputModels(bool $shouldOutputModels): self
    {
        $this->shouldOutputModels = $shouldOutputModels;

        return $this;
    }

    public function open()
    {
        $this->lineCount = 0;

        $this->results = $this->shouldOutputModels ? Collection::make() : null;
    }

    public function close()
    {
        //
    }

    public function append($content)
    {
        if (! $content instanceof Model) {
            throw new \RuntimeException('Content for EloquentSerializer should be an Eloquent model instance');
        }

        $model = $this->saveModel($content);

        if (! $model->exists) {
            throw new \RuntimeException('Could not save Eloquent model');
        }

        $this->incrementLineCount();

        if ($this->shouldOutputModels) {
            $this->results->push($model);
        }
    }

    public function configurator(): EloquentSerializerConfigurator
    {
        return $this->configurator;
    }

    public function output(): ?Collection
    {
        return $this->results;
    }

    public function defaultRecordSerializer(): EloquentRecordSerializer
    {
        return new EloquentRecordSerializer($this->builder);
    }

    protected function saveModel(Model $model)
    {
        // existing record fetched from database
        if ($model->exists) {
            $model->save();

            return $model;
        }

        // new record with no key
        if (\is_null($model->getKey())) {
            $model->save();

            return $model;
        }

        $newInstance = $this->builder->findOrNew($model->getKey());
        $newInstance->setRawAttributes($model->getAttributes())->save();

        return $newInstance;
    }
}
