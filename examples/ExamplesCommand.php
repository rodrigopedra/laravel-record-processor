<?php

namespace RodrigoPedra\LaravelRecordProcessor\Examples;

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Eloquent\Builder;
use RodrigoPedra\LaravelRecordProcessor\Configurators\Serializers\EloquentSerializerConfigurator;
use RodrigoPedra\LaravelRecordProcessor\ProcessorBuilder;
use RodrigoPedra\RecordProcessor\Examples\ExamplesCommand as BaseExamplesCommand;
use RodrigoPedra\RecordProcessor\ProcessorBuilder as BaseProcessorBuilder;

class ExamplesCommand extends BaseExamplesCommand
{
    protected function availableParsers(): string
    {
        return parent::availableParsers() . '|eloquent|query-builder';
    }

    protected function availableSerializers(): string
    {
        return parent::availableSerializers() . '|eloquent|query-builder';
    }

    protected function makeBuilder(): ProcessorBuilder
    {
        return new ProcessorBuilder();
    }

    /**
     * @param  \RodrigoPedra\RecordProcessor\ProcessorBuilder&\RodrigoPedra\LaravelRecordProcessor\ProcessorBuilder  $builder
     * @param  string  $reader
     * @return \RodrigoPedra\LaravelRecordProcessor\ProcessorBuilder
     */
    protected function readFrom(BaseProcessorBuilder $builder, string $reader): BaseProcessorBuilder
    {
        if ($reader === 'eloquent') {
            $builder->withRecordParser(new ExampleLaravelBuilderParser());

            $eloquentBuilder = $this->makeEloquentBuilder();
            $eloquentBuilder->take(10);

            return $builder->readFromEloquent($eloquentBuilder);
        }

        if ($reader === 'query-builder') {
            $builder->withRecordParser(new ExampleLaravelBuilderParser());

            $eloquentBuilder = $this->makeEloquentBuilder();
            $eloquentBuilder->take(10);
            $eloquentBuilder->select(['name', 'email']);

            return $builder->readFromQueryBuilder($eloquentBuilder->getQuery());
        }

        return parent::readFrom($builder, $reader);
    }

    /**
     * @param  \RodrigoPedra\RecordProcessor\ProcessorBuilder&\RodrigoPedra\LaravelRecordProcessor\ProcessorBuilder  $builder
     * @param  string  $serializer
     * @return \RodrigoPedra\LaravelRecordProcessor\ProcessorBuilder
     */
    protected function serializeTo(BaseProcessorBuilder $builder, string $serializer): BaseProcessorBuilder
    {
        if ($serializer === 'eloquent') {
            $eloquentBuilder = $this->makeEloquentBuilder();

            return $builder->writeToEloquent($eloquentBuilder, function (EloquentSerializerConfigurator $configurator) {
                $configurator->withRecordSerializer(new ExampleLaravelBuilderSerializer());
                $configurator->withShouldOutputModels(true);
            });
        }

        if ($serializer === 'query-builder') {
            $eloquentBuilder = $this->makeEloquentBuilder();

            return $builder->writeToQueryBuilder($eloquentBuilder->getQuery());
        }

        return parent::serializeTo($builder, $serializer);
    }

    protected function storagePath(string $file): string
    {
        return __DIR__ . '/../storage/' . $file;
    }

    protected function startLaravelConnection(): void
    {
        $this->makeConnection();

        $capsule = new Capsule();

        $capsule->addConnection([
            'driver' => 'sqlite',
            'database' => $this->storagePath('database.sqlite'),
            'prefix' => '',
        ]);

        $capsule->setAsGlobal();

        $capsule->bootEloquent();
    }

    public function makeEloquentBuilder(): Builder
    {
        $this->startLaravelConnection();

        return UserEloquentModel::query();
    }
}
