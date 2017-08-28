<?php

namespace RodrigoPedra\LaravelRecordProcessor\Examples;

use Illuminate\Database\Capsule\Manager as Capsule;
use RodrigoPedra\LaravelRecordProcessor\ProcessorBuilder;
use RodrigoPedra\RecordProcessor\Examples\ExamplesCommand as BaseExamplesCommand;
use RodrigoPedra\RecordProcessor\Helpers\Configurator;

class ExamplesCommand extends BaseExamplesCommand
{
    protected function getAvailableReaders()
    {
        return parent::getAvailableReaders() . '|eloquent|query-builder';
    }

    protected function getAvailableWriters()
    {
        return parent::getAvailableWriters() . '|eloquent|query-builder';
    }

    protected function makeBuilder()
    {
        return new ProcessorBuilder;
    }

    /**
     * @param  ProcessorBuilder $builder
     * @param  string           $reader
     *
     * @return mixed
     */
    protected function readFrom( $builder, $reader )
    {
        if ($reader === 'eloquent') {
            $builder->usingParser( new ExampleLaravelBuilderParser );

            $eloquentBuilder = $this->makeEloquentBuilder();

            $eloquentBuilder->take( 10 );

            return $builder->readFromEloquent( $eloquentBuilder );
        }

        if ($reader === 'query-builder') {
            $eloquentBuilder = $this->makeEloquentBuilder();
            $builder->usingParser( new ExampleLaravelBuilderParser );

            $eloquentBuilder->take( 10 );

            return $builder->readFromQueryBuilder( $eloquentBuilder->getQuery() );
        }

        return parent::readFrom( $builder, $reader );
    }

    /**
     * @param  ProcessorBuilder $builder
     * @param  string           $writer
     *
     * @return mixed
     */
    protected function writeTo( $builder, $writer )
    {
        if ($writer === 'eloquent') {
            $eloquentBuilder = $this->makeEloquentBuilder();

            return $builder->writeToEloquent( $eloquentBuilder, function ( Configurator $configurator ) {
                $configurator->outputModels( true );
            } );
        }

        if ($writer === 'query-builder') {
            $eloquentBuilder = $this->makeEloquentBuilder();

            return $builder->writeToQueryBuilder( $eloquentBuilder->getQuery() );
        }

        return parent::writeTo( $builder, $writer );
    }

    protected function storagePath( $file )
    {
        return __DIR__ . '/../storage/' . $file;
    }

    protected function startLaravelConnection()
    {
        $this->makeConnection();

        $capsule = new Capsule;

        $capsule->addConnection( [
            'driver'   => 'sqlite',
            'database' => $this->storagePath( 'database.sqlite' ),
            'prefix'   => '',
        ] );

        $capsule->setAsGlobal();

        $capsule->bootEloquent();
    }

    protected function makeEloquentBuilder()
    {
        $this->startLaravelConnection();

        $model = new UserEloquentModel;

        return $model->newQuery();
    }
}
